# maybe you need:
#  sudo apt install python3-pip
#  pip install faker

# important: first you need to create category_data.csv (for isbns)
#  we choose category_data instead of book_data because it has smaller size which is faster. This is no problem bc it contains all isbns.

from faker import Faker
import csv

usernames = [] # here we save usernames to use in multiple tables
isbns = [] # get isbns from book_data.csv

def generate_data(filename, num_data, attributes):
    fake = Faker()
    data = []
    for _ in range(num_data):
        row = []
        for attr in attributes:
            if attr == 'email':
                row.append(fake.email())
            elif attr == 'id':
                row.append(fake.random_int())
            elif attr == 'name':
                row.append(fake.name())
            elif attr == 'password':
                row.append(fake.password())
            # Add conditions for other attribute types as needed
            # ...
        data.append(row)
    
    with open(filename, 'w', newline='') as csvfile:
        writer = csv.writer(csvfile)
        writer.writerow(attributes)
        writer.writerows(data)

# Define the list of specifications
specs = [
    {
        'filename': 'school.csv',
        'num_data': 5,
        'attributes': ['id', 'email']
    },
    {
        'filename': 'user.csv',
        'num_data': 3,
        'attributes': ['name', 'password']
    }
]


# get isbns
with open("../generated_csv/category_data.csv", 'r') as file:
	reader = csv.reader(file)
	for row in reader:
		isbn = row[0]
		if isbn not in isbns:
			isbns.append(isbn)

print("got isbns ("+str(len(isbns))+").")





# Generate data for each specification
for spec in specs:
    generate_data(spec['filename'], spec['num_data'], spec['attributes'])

