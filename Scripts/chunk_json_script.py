import json

data100 = []
data = []
with open('complete_data.json') as file:    
    data = list(json.load(file))
    start = 5
    end = 13
    while start <= end:
        for i in range(0, 1334):
            if data[i]["id"] >= (start * 100 + 1) and data[i]["id"] <= ((start+1) * 100):
                data100.append(data[i])
        start += 1
        jsonfile = 'data' + str(start) + '.json'
        with open(jsonfile, 'w') as file:  
            json.dump(data100, file, indent=4)
            data100 = []