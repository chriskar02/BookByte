# inputs: csv file, table name, table attributes ordered
# output: bookbyte-insert-data.sql containing: insert into r (A1,A2,A3) values (V1,V2,V3);
# note: attributes must be in the same order as in the csv file, NOT like in the schema
# mysql can insert int values in "123" format, so i use quotes to destinct between (all) the values.
# use the function to insert data in the same order as the tables are created to avoid FK contrint errors. Eg insert data in school or book first
# attributes must match exactly the names in the create table (schema)

import csv

def csv_to_sql(input_csv, table_name, attributes_ordered):
	with open(input_csv, 'r') as file:
		reader = csv.reader(file)
		with open('bookbyte-insert-data.sql', 'a') as file2:
			for row in reader:
				values = [];
				for col in row:
					value = col.replace('"','') #remove quotes
					values.append(value)
				result = '';
				for i in range(len(values)-1):
					result += '"'+values[i]+'",'
				result += '"'+values[-1]+'"'
			
				sql_line = 'insert into '+table_name+' ('+attributes_ordered+') values ('+result+');'

				print(sql_line)
				file2.write(sql_line+'\n')		

# main
csv_to_sql('generated_csv/book.csv', 'book', 'title,publisher,isbn,pages,summary,cover_image,language,keywords')
csv_to_sql('generated_csv/author.csv', 'author', 'isbn,name')
csv_to_sql('generated_csv/category.csv', 'category', 'isbn,category')





