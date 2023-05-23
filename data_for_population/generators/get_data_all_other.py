# maybe you need:
#  sudo apt install python3-pip
#  pip install faker

# important: first you need to create category_data.csv (for isbns)
#  we choose category_data instead of book_data because it has smaller size which is faster. This is no problem bc it contains all isbns.
# sch_id is randint(1,number_of_schools), afou to id sto school einai auto_increment
# mariadb converts boolean to tinyint so instad of true, flase we use 1,0

from faker import Faker
import csv
import random

#options
debug = 0

#params
number_of_schools = 6
number_of_users = 60
max_number_of_schools = 50 #true is max_number_of_schools - 4
maximum_n_of_copies_per_sch = 6

def printres(title,value,goal):
	COL = '\033[0;32m' #green
	if value < goal:
		COL = '\033[0;35m' #red
	print(title+":",COL,value,">",goal,'\033[0m')


#program vars
fake = Faker()
usernames = [] # here we save usernames to use in multiple tables
teacher_usernames = []
isbns = [] # get isbns from book_data.csv
handler_usernames = []
user_schools = {} #(username, sch_id)
user_loan_and_reserves = [] #array of isbns that have not been returned or are reserved by anyone: to avoid conflicts, the same isbn cannot be reserved by two or more users, even if there is availiablility. To test this feature make reservations and loans manually from UI.

# ==========================
#	begin
# ==========================
print("\n\n========== begin ==========\n")

# get isbns
with open("../generated_csv/category.csv", 'r') as file:
	reader = csv.reader(file)
	for row in reader:
		isbn = row[0]
		if isbn not in isbns:
			isbns.append(isbn)
printres("availiable books",len(isbns),100)


# ==========================
#	school.csv
# ==========================
data = []
counter = 0
for i in range(number_of_schools):
	counter+=1
	row = []
	
	sch_name = str(fake.unique.random_int(min=4, max=max_number_of_schools)) + "th "+ str(fake.random_element(["High School", "Elementary School", "Middle School"]))
	
	row.append(sch_name)
	row.append(fake.unique.email())
	row.append(fake.unique.street_name()+" "+str(random.randint(2,300)))
	
	row.append(fake.city())
	row.append(fake.name())
	row.append("210"+str(random.randint(5000,9999999)).zfill(7))

	if debug: print(row,"\n\n")
	data.append(row)
with open('school.csv', 'w', newline='') as csvfile:
	writer = csv.writer(csvfile)
	writer.writerows(data)
printres("schools",counter,3)


# ==========================
#	user.csv
# ==========================
data = []
counter = 0
for i in range(number_of_users):
	counter+=1
	row = []
	u = fake.unique.user_name()
	usernames.append(u)
	
	sch_id = random.randint(1, number_of_schools)
	user_schools[u] = sch_id
	
	row.append(u)
	row.append("defaultpass"+str(random.randint(10, 100)))
	row.append(fake.unique.email())
	row.append(fake.name())
	row.append(str(sch_id))
	
	ver = "0"
	if random.randint(1,100) < 50: #prob of being verified
		ver = "1"
	
	row.append(ver)
	if debug: print(row,"\n\n")
	data.append(row)
with open('user.csv', 'w', newline='') as csvfile:
	writer = csv.writer(csvfile)
	writer.writerows(data)
printres("users",counter,40)

# ==========================
#	teacher.csv
# ==========================
data = []
counter = 0
counter2 = 0
for i in usernames:
	if random.randint(1,100) < 31: #prob of being a teacher
		counter+=1
		row = []
		row.append(i)
		teacher_usernames.append(i)
		
		req = "0"
		ver = "0"
		if random.randint(1,100) < 45: #prob of requesting
			req = "1"
			if random.randint(1,100) < 75: #prob of being a handler
				ver = "1"
				handler_usernames.append(i)
				counter2+=1
		row.append(req) #handler_req
		row.append(str(random.randint(1960,2003))+"-"+str(random.randint(1,12)).zfill(2)+"-"+str(random.randint(1,27)).zfill(2)) #birth date
		row.append(ver) #handler_req
		if debug: print(row,"\n\n")
		data.append(row)
with open('teacher.csv', 'w', newline='') as csvfile:
	writer = csv.writer(csvfile)
	writer.writerows(data)
printres("teachers",counter,10)
printres("handlers",counter2,1)

# ==========================
#	admin.csv
# ==========================
data = []
counter = 0
for i in usernames:
	if random.randint(1,100) < 10: #prob of being an admin
		counter += 1
		row = []
		row.append(i)
		if debug: print(row,"\n\n")
		data.append(row)
with open('admin.csv', 'w', newline='') as csvfile:
	writer = csv.writer(csvfile)
	writer.writerows(data)
printres("admins",counter,1)

# ==========================
#	school_storage.csv
# ==========================
data = []
counter = 0
counter2 = 0
for j in isbns:
	counter = 0
	for i in range(1,number_of_schools+1):
		if random.randint(1,100) < 38: #prob of having book
			counter += 1
			row = []
			row.append(i)
			row.append(j)
			copies = random.randint(1,maximum_n_of_copies_per_sch)
			counter2 += copies
			row.append(str(copies)) #number of copies
			if debug: print(row,"\n\n")
			data.append(row)
	if counter == 0:
		#every book is availiable somewhere (only for generated data)
		counter += 1
		row = []
		row.append(1) #if nowhere else, sch with id==1 has it
		row.append(j)
		copies = random.randint(1,maximum_n_of_copies_per_sch)
		counter2 += copies
		row.append(str(copies)) #number of copies
		if debug: print(row,"\n\n")
		data.append(row)
with open('school_storage.csv', 'w', newline='') as csvfile:
	writer = csv.writer(csvfile)
	writer.writerows(data)
print("total book copies:",counter2)
print("avg n of copies per book:",counter2/len(isbns))
print("avg n of copies per school:",counter2/number_of_schools)

# ==========================
#	ratings.csv
# ==========================
data = []
counter = 0
for i in usernames:
	for j in isbns:
		if random.randint(1,100) < 25: #prob of having rated this book
			counter+=1
			row = []
			row.append(i)
			row.append(j)
			row.append(str(random.randint(1,5))) #stars
			row.append(fake.paragraph()) #description

			ver = "1"
			rnd = random.randint(1,10)
			if rnd <= 5:
				ver = "0"
			row.append(ver) #verified

			if debug: print(row,"\n\n")
			data.append(row)

with open('ratings.csv', 'w', newline='') as csvfile:
	writer = csv.writer(csvfile)
	writer.writerows(data)
print("total ratings: ",counter)

# ==========================
#	loan.csv
# ==========================
data = []
counter = 0
counter2 = 0
for i in range(len(usernames)):
	if len(handler_usernames) == 0:
		print("there are no handlers! increase the probability.")
		break;
	loans = 0
	for j in isbns:
		isTeacher = False
		if i in teacher_usernames:
			isTeacher = True
		if isTeacher and loans > 0:
			break
		if not isTeacher and loans > 1:
			break
		if random.randint(1,len(isbns)+70) < 3: #prob of having borrowed this book
			counter+=1
			loans+=1
			row = []
			rand_handler = random.choice(handler_usernames)
			row.append(usernames[i])
			row.append(j)
			row.append(rand_handler)
			row.append(str(random.randint(2023,2023)) + "-" + str(random.randint(1,12)).zfill(2) + "-" + str(random.randint(1,27)).zfill(2) + " " + str(random.randint(1,23)).zfill(2) + ":" + str(random.randint(1,59)).zfill(2)+ ":" + str(random.randint(1,59)).zfill(2))
			row.append(user_schools[rand_handler])
			row.append('borrowed') #in_out

			if debug: print(row,"\n\n")
			data.append(row)
			# if borrowed, then:
			if random.randint(1,100) < 40: #prob of having returned it if borrowed
				counter2+=1
				rand_handler = random.choice(handler_usernames)
				row = []
				row.append(usernames[i])
				row.append(j)
				row.append(rand_handler)
				row.append(str(random.randint(2023,2023)) + "-" + str(random.randint(1,12)).zfill(2) + "-" + str(random.randint(1,27)).zfill(2) + " " + str(random.randint(1,23)).zfill(2) + ":" + str(random.randint(1,59)).zfill(2)+ ":" + str(random.randint(1,59)).zfill(2)) #greater than borrowing date
				row.append(user_schools[rand_handler])
				row.append('returned') #in_out
	
				if debug: print(row,"\n\n")
				data.append(row)
			else:
				#if borrowed but not returned, add to list
				user_loan_and_reserves.append(j)

with open('loan.csv', 'w', newline='') as csvfile:
	writer = csv.writer(csvfile)
	writer.writerows(data)
printres("total borrows",counter,50)
print("total returns: ",counter2)

# ==========================
#	reservation.csv
# ==========================
data = []
counter = 0
counter2 = 0
for i in usernames:
	reservations = 0
	for j in isbns:
		if j in user_loan_and_reserves:
			continue
		isTeacher = False
		if i in teacher_usernames:
			isTeacher = True
		if isTeacher and reservations > 0:
			break
		if not isTeacher and reservations > 1:
			break
		if random.randint(1,len(isbns)+60) < 4: #prob of making this reservation
			user_loan_and_reserves.append(j)
			counter+=1
			reservations+=1
			row = []
			row.append(i)
			row.append(j)
			row.append(str(random.randint(2023,2023)) + "-" + str(random.randint(1,12)).zfill(2) + "-" + str(random.randint(1,27)).zfill(2) + " " + str(random.randint(1,23)).zfill(2) + ":" + str(random.randint(1,59)).zfill(2)+ ":" + str(random.randint(1,59)).zfill(2))
			row.append(random.randint(1, number_of_schools)) #vazoume random school kai h mariadb tha kopsei osa sxoleia den exoun diathesimothta

			if debug: print(row,"\n\n")
			data.append(row)
with open('reservation.csv', 'w', newline='') as csvfile:
	writer = csv.writer(csvfile)
	writer.writerows(data)
printres("total reservations: ",counter,40)



print("\n||=== check if the above values are good, or (adjust parameters and probabilities and) run it again ===||")

print("||=== ypenthymhsh apo ekfwnhsh: ENDEIKTIKA: students>30, teachers>10 reservations>40, borrows>50, books>100, schools>3 ===||")



