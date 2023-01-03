import requests 
import time
import os
import pandas as ps
path = 'ProductosNuks.xlsx'
ex= ps.read_excel(path)
url = ex.loc[:,'urlimg'].dropna().tolist()
def downloadImg (ruta,name): 

    img = requests.get(ruta).content
    with open("img/product/"+name+".jpg",'wb') as  handler:
        handler.write(img)
        return name 

for r in url:
    name= r.split("/")[-1]
    downloadImg(r,name)



