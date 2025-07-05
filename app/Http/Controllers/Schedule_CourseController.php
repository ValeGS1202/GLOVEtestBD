<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Schedule_Course;

class Schedule_CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        \Log::info("🟢 Entrando a Schedule_CourseController@store");
        // Recibe un PDF, lo convierte a texto, lo manda a OpenAI, y guarda los cursos en la tabla
        if (!$request->hasFile('pdf')) {
            return response()->json(['error' => 'No PDF uploaded'], 400);
        }

        $pdfFile = $request->file('pdf');
        \Log::info("📄 Archivo recibido: " . $pdfFile->getClientOriginalName());
        $pdfPath = $pdfFile->getRealPath();

        // Extraer texto del PDF usando helper
        try {
            $text = \App\Http\Controllers\PdfOpenAIService::extractTextFromPdf($pdfPath);
            \Log::info("✅ Texto extraído del PDF");
        } catch (\Exception $e) {
            return response()->json(['error' => 'PDF parse error', 'details' => $e->getMessage()], 500);
        }

        // Llamar a OpenAI API
        $openaiApiKey = env('OPENAI_API_KEY');
        if (!$openaiApiKey) {
            return response()->json(['error' => 'OPENAI_API_KEY not set in .env'], 500);
        }
        try {
            $raw = \App\Http\Controllers\PdfOpenAIService::sendToOpenAI($text, $openaiApiKey);
            \Log::info("✅ Respuesta recibida de OpenAI");
        } catch (\Exception $e) {
            \Log::error("❌ Error al enviar a OpenAI", ['msg' => $e->getMessage()]);
            return response()->json(['error' => 'OpenAI error', 'details' => $e->getMessage()], 500);
        }

        // Intentar decodificar el JSON
        $courses = null;
        try {
            $courses = json_decode($raw, true);
        } catch (\Exception $e) {
            return response()->json(['error' => 'OpenAI response is not valid JSON', 'content' => $raw], 500);
        }
        if (!is_array($courses)) {
            return response()->json(['error' => 'OpenAI response is not a valid array', 'content' => $raw], 500);
        }

        // Guardar cada curso en la tabla Schedule_Course
        $inserted = [];
        foreach ($courses as $course) {
            try {
                $sc = new \App\Models\Schedule_Course();
                $sc->course_code = $course['SG'] ?? null;
                $sc->name = $course['Na'] ?? null;
                $sc->credits = $course['Cr'] ?? null;
                $sc->setAttribute('group', $course['Gr'] ?? null);
                $sc->schedule_list = isset($course['LH']) ? json_encode($course['LH']) : null;
                $sc->format = $course['M'] ?? null;
                $sc->requirements = isset($course['Req']) ? json_encode($course['Req']) : null;
                $sc->corequisites = isset($course['CoReq']) ? json_encode($course['CoReq']) : null;
                $sc->save();
                $inserted[] = $sc;
            } catch (\Exception $e) {
                \Log::error("Error guardando curso: ".$e->getMessage(), ['curso' => $course]);
            }
        }

        return response()->json(['inserted' => count($inserted), 'data' => $inserted]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function all(){

        $schedule_courses = Schedule_Course::all();
        return response()->json($schedule_courses);
    }

    public function procesarPDF(Request $request)
    {
        if (!$request->hasFile('archivo')) {
            return response()->json(['error' => 'No se envió ningún archivo.'], 400);
        }

        $file = $request->file('archivo');
        $text = PdfOpenAIService::extractTextFromPdf($file->getPathname());

        $apiKey = env('OPENAI_API_KEY');
        $jsonResponse = PdfOpenAIService::sendToOpenAI($text, $apiKey);

        $cursos = json_decode($jsonResponse, true);

        if ($cursos === null) {
            return response()->json(['error' => 'No se pudo interpretar la respuesta de OpenAI como JSON'], 500);
        }

        foreach ($cursos as $curso) {
            Schedule_Course::create([
                'schedule_id' => 1,
                'course_code'   => $curso['SG'] ?? null,
                'name'          => $curso['Na'] ?? null,
                'credits'       => $curso['Cr'] ?? null,
                'group'         => $curso['Gr'] ?? null,
                'schedule_list' => json_encode($curso['LH'] ?? []),
                'format'        => $curso['M'] ?? null,
                'requirements'  => json_encode($curso['Req'] ?? []),
                'corequisites'  => json_encode($curso['CoReq'] ?? []),
            ]);
        }

        return response()->json(['success' => true, 'cursos_guardados' => count($cursos)]);
    }

}
