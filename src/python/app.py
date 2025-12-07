import cv2
import requests
from fastapi import FastAPI, File, UploadFile
from pydantic import BaseModel
import numpy as np

# ==== CONFIG ====
IMG_API_URL = "https://risetkami-risetkami.hf.space/predict_face"   # ubah ke URL space HF nanti
TXT_API_URL = "https://risetkami-risetkami.hf.space/predict_text"
# IMAGE_PATH = "densenet_architecture.jpg"  # path gambar lokal
# payload = {"text": "saya tidak mau berangkat kuliah"}
headers = {
    "Content-Type": "application/json"
}
app=FastAPI()

class TextParams(BaseModel) :
    text : str

@app.post('/text')
def text_process(data : TextParams) :
    response = requests.post(TXT_API_URL, json=data.dict(), headers=headers)
    return {"data" : data.dict(), "response" : response.json()}

@app.post('/image')
async def image_process(file : UploadFile=File(...)) :
    # ==== LOAD IMAGE DENGAN OPENCV ====\
    contents = await file.read()
    np_arr = np.frombuffer(contents, np.uint8)
    img = cv2.imdecode(np_arr, cv2.IMREAD_COLOR)
    if img is None:
        return {"response" : "Gambar tidak valid"}
    # OPSIONAL: encode jadi bytes JPG sebelum kirim
    _, img_encoded = cv2.imencode('.jpg', img)
    # ==== REQUEST ====
    files = {
        "file": (file.filename, img_encoded.tobytes(), "image/jpeg")
    }
    response = requests.post(IMG_API_URL, files=files)
    # ==== CETAK HASIL ====
    if response.status_code == 200:
        return {"data" : file.filename, "response" : response.json()}
    else:
        return {"response" : response.text}


