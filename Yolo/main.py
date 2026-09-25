from fastapi import FastAPI, UploadFile, File
from ultralytics import YOLO

app = FastAPI()

model = YOLO("best.pt")


@app.post("/detectar")
async def detectar(file: UploadFile = File(...)):

    imagem = await file.read()

    print("Nome recebido:", file.filename)
    print("Tipo recebido:", file.content_type)
    print("Tamanho recebido:", len(imagem), "bytes")

    with open("foto.jpg", "wb") as arquivo:
        arquivo.write(imagem)

    results = model("foto.jpg")

    objetos = []

    for result in results:

        for box in result.boxes:

            classe = int(box.cls[0])

            nome = result.names[classe]

            confianca = float(box.conf[0])

            print(
                "Objeto:",
                nome,
                "| Confiança:",
                confianca
            )

            objetos.append({
                "nome": nome,
                "confianca": confianca
            })

    return {
        "objetos": objetos
    }