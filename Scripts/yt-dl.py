# working stable version

from pytube import Playlist
from pytube import YouTube
from pydub import AudioSegment
from pydub.utils import make_chunks
import speech_recognition as sr
import subprocess
import json
import re
import os


def audio_to_text(filename, text):
    try:
        os.makedirs('chunked')
    except:
        pass

    raw_audio = AudioSegment.from_wav(filename)
    chunk_len_ms = 8000
    chunks = make_chunks(raw_audio, chunk_len_ms)
    
    for i, chunk in enumerate(chunks):
        chunk_name = './chunked/' + filename + '_{0}.wav'.format(i)
        print('Exporting', chunk_name)
        chunk.export(chunk_name, format='wav')
        r = sr.Recognizer()
        with sr.AudioFile(chunk_name) as source:
            audio_listened = r.listen(source)
            for i in range(0,100):
                try:
                    words_recognized = r.recognize_google(audio_listened)
                    text += ' ' + words_recognized
                    break
                except sr.UnknownValueError:
                    print('Audio not recognized.')
                    break
                except sr.RequestError:
                    print('Check internet.')
                except sr.WaitTimeoutError:
                    print('Time out error: host failed to respond. Will try again.')
        
    dir = 'C:/Users/dbria/Documents/GitHub/youtubeDL/chunked'
    for f in os.listdir(dir):
        os.remove(os.path.join(dir, f))
    
    return text


if __name__ == "__main__":
    #playlist = Playlist(input('Enter playlist URL: '))
    playlist = Playlist("https://www.youtube.com/playlist?list=PLX_rhFRRlAG58_4z9KWPUYrnTM6QZDJrT")
    print('Downloading ' + playlist.title)

    print('Destination? (Leave blank to use current directory)')
    output_path = input('>> ')

    e_flag = 0
    for track_num, video in enumerate(playlist.videos):
        if track_num + 1 <= 204:
            continue
        if track_num + 1 == 207:
            break
        print('Downloading Track ' + str(track_num + 1) + ' of ' + str(playlist.length) + ' : ' + video.title)
        d = {}
        vid_url = playlist.video_urls[track_num]
        text = ''
        en_caption_data = ''
       
        try:
            en_caption_data = YouTube(vid_url).captions['a.en'].generate_srt_captions()
            en_caption_data_li = en_caption_data.split('\n')
            for line in en_caption_data_li:
                if re.search('^[0-9]+$', line) is None and re.search('^[0-9]{2}:[0-9]{2}:[0-9]{2}', line) is None and re.search('^$', line) is None:
                    text += ' ' + line.rstrip('\n')
                text = text.lstrip()
        except: 
            e_flag = 1
            print('No captions found. Downloading mp3 to do speech to text.')
            try:
                video_mp3 = video.streams.filter(only_audio=True).first()
                output_file = video_mp3.download(output_path=output_path, filename='test.mp4')
                command = "ffmpeg -y -i C:/Users/dbria/Documents/GitHub/youtubeDL/test.mp4 -ab 160k -ac 2 -ar 44100 -vn temp.wav"
                subprocess.call(command, shell=True)                                                 
                text = audio_to_text('temp.wav', text)
            except KeyError:
                print('Video deemed inappropriate or offensive by YT. Unable to download.')
                continue

        
        d['id'] = track_num + 1
        d['title'] = video.title
        d['transcript'] = text
        d['srt'] = en_caption_data
        d['link'] = vid_url
        
        track_num += 1
        with open('data.json') as file:    
            old_data = json.load(file)
            old_data = list(old_data)
            old_data.append(d)
        with open('data.json', 'w') as file:  
            json.dump(old_data, file, indent=4)
    print('Successfully downloaded ' + str(playlist.length) + ' tracks!')

    