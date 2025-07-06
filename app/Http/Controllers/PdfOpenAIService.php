<?php

namespace App\Http\Controllers;

class PdfOpenAIService
{
    public static function extractTextFromPdf($pdfPath)
    {
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($pdfPath);
        return $pdf->getText();
    }

    public static function sendToOpenAI($text, $apiKey)
    {
        $systemPrompt = <<<EOT

Eres un sistema que procesa información de horarios académicos extraída de un PDF.

El contenido que procesas puede estar tabulado o en formato texto, y puede incluir datos fragmentados como bloques adicionales de horario, aula, requisitos o correquisitos en líneas posteriores.

Extrae todos los cursos únicamente de la carrera de “Bachillerato y Licenciatura en Informática y Tecnología Multimedia (Esparza)”, ignorando cualquier otra carrera.

Devuelve la información en formato JSON como una lista de objetos. Cada curso debe tener el siguiente esquema con nombres de campos abreviados:

SG: Sigla (por ejemplo, "TM0108")

Na: Nombre completo del curso

Cr: Créditos

Gr: Grupo (número de grupo)

LH: Lista de horarios (cada uno como string, ejemplo: "S 13:00-16:50", "L, J 08:00-11:50")

M: Modalidad (por ejemplo, "VIRTUAL", "PRESENCIAL", "BIMODAL", "BAJO VIRTUAL")

Req: Requisitos (como lista de siglas, por ejemplo ["TM8500"]) si aparecen

CoReq: Correquisitos (como lista de siglas, por ejemplo ["TM8100", "TM1300"]) si aparecen

Notas importantes:

Si un curso tiene varios horarios o bloques, agrega todos al campo H como una lista de strings.

Si un curso tiene varios grupos, repórtalos como objetos distintos (uno por grupo).

Si los requisitos o correquisitos aparecen en líneas posteriores, asócialos al curso correcto por su sigla.

Los LH o lista de horarios deben de venir de la siguiente manera:
["K:13:00-16:50, V 13:00-16:50"] aunque sean dos dias distintos con las mismas horas, deben de venir separados.

Este es un ejemplo de como NO debe de venir el LH: L, J 08:00-11:50

Los dias no deben de venir separados por comas, sino que deben de venir con sus horarios, asi K:13:00-16:50, V 13:00-16:50.

Si en el PDF encuentras un horario que venga de esa forma, interpretalo y corrigelo.

Devuelve solamente el JSON, sin texto adicional. ejemplo del json esperado:
[
 {
    "SG": "TM2100",
    "Na": "FUNDAMENTOS DE PROGRAMACIÓN",
    "Cr": 7,
    "Gr": 1,
    "LH": ["K:13:00-16:50, V 13:00-16:50"],
    "M": "BIMODAL"
    "Req": ["TM1100"],
    "CoReq": ["TM2200", "TM2300"],
     },
  {
    "SG": "TM1300",
    "Na": "FUNDAMENTOS DE DIBUJO",
    "Cr": 4,
    "Gr": 2,
    "LH": ["V 13:00-16:50"],
    "M": "PRESENCIAL"
    "Req": [""],
    "CoReq": ["TM2300"]

  }

]

EOT;

        $payload = [
            'model' => 'gpt-4.1',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $text],
            ],
            'temperature' => 0.1,
        ];

        $client = new \GuzzleHttp\Client();
        $res = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => $payload,
            'timeout' => 60,
        ]);
        $data = json_decode($res->getBody(), true);
        return $data['choices'][0]['message']['content'] ?? null;
    }
}
