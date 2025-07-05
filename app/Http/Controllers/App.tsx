/*import React, { useState } from "react";
import { GlobalWorkerOptions, getDocument } from "pdfjs-dist";
import type { PDFDocumentProxy } from "pdfjs-dist/types/src/display/api";
import pdfjsWorker from "pdfjs-dist/build/pdf.worker?url"; // 👈 el truco correcto

GlobalWorkerOptions.workerSrc = pdfjsWorker;


const App: React.FC = () => {
  const [pdfText, setPdfText] = useState("");
  const [loading, setLoading] = useState(false);
  const [jsonResponse, setJsonResponse] = useState<any[]>([]);
  const [openAIError, setOpenAIError] = useState<string | null>(null);

  // PDF to text extraction
  const handlePDF = (event: React.ChangeEvent<HTMLInputElement>) => {
    const file = event.target.files?.[0];
    if (!file) return;
    setPdfText("");
    setJsonResponse([]);
    setOpenAIError(null);

    const reader = new FileReader();
    reader.onload = async (e) => {
      const typedArray = new Uint8Array(e.target?.result as ArrayBuffer);
      const pdf: PDFDocumentProxy = await getDocument({ data: typedArray })
        .promise;
      let out = "";
      for (let i = 1; i <= pdf.numPages; i++) {
        const page = await pdf.getPage(i);
        const textContent = await page.getTextContent();
        out += textContent.items.map((item: any) => item.str).join(" ") + "\n";
      }
      setPdfText(out.trim());
    };
    reader.readAsArrayBuffer(file);
  };

  // Send to OpenAI
  const handleSend = async () => {
    setLoading(true);
    setJsonResponse([]);
    setOpenAIError(null);

    const systemPrompt = `
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
`;
    try {
      const res = await fetch("https://api.openai.com/v1/chat/completions", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${OPENAI_API_KEY}`,
        },
        body: JSON.stringify({
          model: "gpt-4.1-mini",
          messages: [
            { role: "system", content: systemPrompt },
            { role: "user", content: pdfText },
          ],
          temperature: 0.1,
        }),
      });

      const data = await res.json();
      console.log("OpenAI FULL RESPONSE:", data);

      if (data.error) {
        setOpenAIError(data.error.message || "Error from OpenAI");
      } else {
        const raw = data.choices?.[0]?.message?.content;
        console.log("OpenAI RAW CONTENT:", raw);
        // Try to parse as JSON
        try {
          const parsed = JSON.parse(raw);
          if (Array.isArray(parsed)) setJsonResponse(parsed);
          else setJsonResponse([parsed]);
        } catch {
          setJsonResponse([
            { error: "Respuesta no es un JSON válido", content: raw },
          ]);
        }
      }
    } catch (err: any) {
      setOpenAIError(String(err));
    }
    setLoading(false);
  };

  return (
    <div
      style={{ maxWidth: 800, margin: "2rem auto", fontFamily: "sans-serif" }}
    >
      <h1>Extractor de carreras desde PDF + OpenAI</h1>
      <input type="file" accept="application/pdf" onChange={handlePDF} />
      {pdfText && (
        <div>
          <h2>Texto extraído del PDF:</h2>
          <textarea
            style={{ width: "100%" }}
            value={pdfText}
            rows={10}
            readOnly
          />
          <br />
          <button onClick={handleSend} disabled={loading}>
            {loading ? "Consultando OpenAI..." : "Enviar a OpenAI"}
          </button>
        </div>
      )}

      {openAIError && (
        <div style={{ color: "red", marginTop: 20 }}>Error: {openAIError}</div>
      )}

      {jsonResponse.length > 0 && Array.isArray(jsonResponse[0].cursos) && (
        <>
          <h2>Respuesta de OpenAI como tabla</h2>
          <table
            border={1}
            style={{ borderCollapse: "collapse", width: "100%" }}
          >
            <thead>
              <tr>
                <th>Carrera</th>
                <th>Siglas Carrera</th>
                <th>Curso</th>
                <th>Sigla Curso</th>
                <th>Horario</th>
                <th>Modalidad</th>
                <th>Prerequisitos</th>
                <th>Correquisitos</th>
              </tr>
            </thead>
            <tbody>
              {jsonResponse.map((career, ix) =>
                (career.cursos || []).map((curso: any, jx: number) => (
                  <tr key={ix + "-" + jx}>
                    <td>{career.nombre}</td>
                    <td>{career.siglas}</td>
                    <td>{curso.nombre}</td>
                    <td>{curso.sigla}</td>
                    <td>{curso.horario}</td>
                    <td>{curso.modalidad}</td>
                    <td>
                      {Array.isArray(curso.prerequisitos)
                        ? curso.prerequisitos.join(", ")
                        : String(curso.prerequisitos ?? "")}
                    </td>
                    <td>
                      {Array.isArray(curso.correquisitos)
                        ? curso.correquisitos.join(", ")
                        : String(curso.correquisitos ?? "")}
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </>
      )}

      {jsonResponse.length === 1 && jsonResponse[0].error && (
        <div style={{ marginTop: 20 }}>
          <strong>Respuesta de OpenAI:</strong>
          <pre>{jsonResponse[0].content}</pre>
        </div>
      )}
    </div>
  );
};

export default App;
*/