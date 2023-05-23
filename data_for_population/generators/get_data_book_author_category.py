import requests
import csv
import subprocess
import random
import string
import tempfile

# List of book categories or topics
categories = ["fiction", "history", "science", "art", "technology"]

# Function to save book information in CSV file
def save_book_info(book_info):
	with open("book_data.csv", "a", newline="", encoding="utf-8") as file:
		writer = csv.writer(file)
		writer.writerow(book_info)
def save_author_info(book_info):
	with open("author_data.csv", "a", newline="", encoding="utf-8") as file:
		writer = csv.writer(file)
		writer.writerow(book_info)
def save_category_info(book_info):
	with open("category_data.csv", "a", newline="", encoding="utf-8") as file:
		writer = csv.writer(file)
		writer.writerow(book_info)

#!!!: when decoding bolb using "base64 -d textfilename.txt > new.jpg" you must remove "b'" from the start and "'" from the end of blob text
def download_image(url):
	temp_filename = tempfile.mktemp(suffix='.jpg')
	subprocess.run(["wget", url, "-O", temp_filename], stdout=subprocess.DEVNULL)
	result = subprocess.run(["base64", "-w", "0", temp_filename], stdout=subprocess.PIPE)
	blob_data = result.stdout.strip()
	#print(blob_data);
	return blob_data

# Function to fetch book information from the Google Books API
def fetch_book_info(category):
	url = f"https://www.googleapis.com/books/v1/volumes?q=subject:{category}"
	response = requests.get(url)
	data = response.json()

	if 'items' in data:
		books = data['items']
		for book in books:
			volume_info = book['volumeInfo']
			title = volume_info.get('title', '')
			publisher = volume_info.get('publisher', '')
			
			isbn = volume_info.get('industryIdentifiers', [{}])[0].get('identifier', '')
			isbn = isbn[-10:].replace(" ", "") #last 10 characters
			isbn = isbn.zfill(10)
			
			pages = volume_info.get('pageCount', 0)
			summary = volume_info.get('description', '')
			cover_image_url = volume_info.get('imageLinks', {}).get('thumbnail', '')
			language = volume_info.get('language', '')
			
			#keywords are up to 9 random words from the summary
			keywords = ""
			sum_spl = summary.split(' ');
			for n in range (9):
				if random.randint(1, 10) > 3:
					ypops = random.choice(sum_spl)
					if len(ypops) < 3: continue
					keywords += ypops+",";
			
			categories = volume_info.get('categories', '')
			authors = volume_info.get('authors', '')
			
			if pages == 0 or summary == '' or keywords == '' or title == '' or language == '' or publisher == '' or isbn == '' or authors == '' or categories == '':
				print('emoty val: continuing')
				continue
			
			# Download cover image and convert to blob
			cover_image_blob = download_image(cover_image_url)
			# Save the book information in the CSV file
			save_book_info([title, publisher, isbn, pages, summary, cover_image_blob, language, keywords])
			for author in authors:
				save_author_info([isbn,author])
			for category2 in categories:
				for category in category2.split(' & '):
					save_category_info([isbn,category])

# number of results is range*10
for i in range(20):
	if i % 5 == 0: print('got',i)
	category = random.choice(categories)
	fetch_book_info(category)

print("Done.")


