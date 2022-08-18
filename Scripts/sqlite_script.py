import sqlite3
import json

conn = sqlite3.connect('data.db')

cursor = conn.cursor()

print('Establishing connection with database.')
try:
    cursor.execute("""CREATE TABLE podcasts (
        id integer,
        title text,
        transcript text,
        srt text,
        link text
    )""")
    print('Table initialized.')
except:
    print('Data.db already exists.')

databank = []
with open('test.json') as file:    
    data = json.load(file)
    for element in data:
        databank.append((element['id'], element['title'], element['transcript'], element['srt'], element['link']))

file.close()

try:
    cursor.executemany("INSERT INTO podcasts (id, title, transcript, srt, link) VALUES (?, ?, ?, ?, ?)", databank)
    conn.commit()
    print('Table populated successfully.')
except:
    print('Executemany failed.')
    conn.rollback()

# TEST
# cursor.execute("SELECT * FROM podcasts WHERE id = 23")
# print(cursor.fetchall())

conn.close()