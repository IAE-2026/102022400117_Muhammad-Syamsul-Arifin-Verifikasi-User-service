import sys
from pypdf import PdfReader

try:
    reader = PdfReader(sys.argv[1])
    text = ""
    for page in reader.pages:
        text += page.extract_text() + "\n"
    with open('pdf_output.txt', 'w', encoding='utf-8') as f:
        f.write(text[:20000])
except Exception as e:
    print("Error:", e)
