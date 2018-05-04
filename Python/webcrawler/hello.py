#!/usr/bin/env python3

from bs4 import BeautifulSoup
import requests
import json
from flask import Flask, jsonify,abort,request



app = Flask(__name__)

@app.route('/post')


def index():
	site_id=request.args.get('id')
	site_url=request.args.get('url')
	response = requests.get(site_url, headers={'User-agent': 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36'})
	soup = BeautifulSoup(response.content,"lxml")
	if(site_id == '1'):
		return getamazondata(soup)

	if(site_id == '2'):
		return getaliexpress(soup)



def getamazondata(soup):
	productTitle = soup.find("span", {"id": "productTitle"}).get_text()
	desc = soup.find("div", {"id": "feature-bullets"}).get_text().replace('\n', ' ').replace('\r', '').replace('\t', '')
	img = soup.find("div", {"class": "imgTagWrapper"}).select("img[src]")[0]['src']
	price = soup.find("span", {"id": "priceblock_ourprice"}).get_text().replace('\n', ' ').replace('\r', '').replace('\t', '')
	scarpdata={}
	scarpdata['title'] = productTitle
	scarpdata['desc'] = desc
	scarpdata['img'] = img
	scarpdata['price'] = price
	return jsonify(scarpdata)


def getaliexpress(soup):
	productTitle = soup.find("h1", {"class": "product-name"}).get_text().replace('\n', ' ').replace('\r', '').replace('\t', '')
	desc = soup.find("div", {"class": "product-desc"}).get_text().replace('\n', ' ').replace('\r', '').replace('\t', '')
	img = soup.find("a", {"class": "ui-image-viewer-thumb-frame"}).select("img[src]")[0]['src']
	price = soup.find("span", {"id": "j-sku-discount-price"}).get_text().replace('\n', ' ').replace('\r', '').replace('\t', '')
	scarpdata={}
	scarpdata['title'] = productTitle
	scarpdata['desc'] = desc
	scarpdata['img'] = img
	scarpdata['price'] = price
	return jsonify(scarpdata)




if __name__ == '__main__':
    app.run(debug=True)