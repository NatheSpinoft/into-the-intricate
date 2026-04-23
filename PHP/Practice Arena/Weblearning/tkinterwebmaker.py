import tkinter as tk
from tkinter import scrolledtext
import webbrowser
import os

def preview():
    html_content = html_text.get("1.0", tk.END)
    css_content = css_text.get("1.0", tk.END)
    js_content = js_text.get("1.0", tk.END)
    filename = filename_entry.get().strip()

    if not filename:
        filename = "preview.html"
    elif not filename.endswith(".html"):
        filename += ".html"

    full_html = f"""
    <html>
    <head>
    <style>{css_content}</style>
    </head>
    <body>
    {html_content}
    <script>{js_content}</script>
    </body>
    </html>
    """

    with open(filename, "w", encoding="utf-8") as f:
        f.write(full_html)
    
    webbrowser.open(f"file://{os.path.abspath(filename)}")

root = tk.Tk()
root.title("Mini HTML Editor")
root.geometry("800x600")  # Default starting size

# Make the window resizable
root.rowconfigure(2, weight=1)
root.rowconfigure(3, weight=1)
root.rowconfigure(4, weight=1)
root.columnconfigure(0, weight=1)

# Filename entry
tk.Label(root, text="Filename:").grid(row=0, column=0, sticky='w', padx=5, pady=2)
filename_entry = tk.Entry(root)
filename_entry.grid(row=1, column=0, sticky='ew', padx=5)
filename_entry.insert(0, "preview.html")

# HTML editor
html_text = scrolledtext.ScrolledText(root)
html_text.grid(row=2, column=0, sticky='nsew', padx=5, pady=5)
html_text.insert(tk.END, "<h1>Hello World</h1>")

# CSS editor
css_text = scrolledtext.ScrolledText(root)
css_text.grid(row=3, column=0, sticky='nsew', padx=5, pady=5)
css_text.insert(tk.END, "h1 { color: red; }")

# JS editor
js_text = scrolledtext.ScrolledText(root)
js_text.grid(row=4, column=0, sticky='nsew', padx=5, pady=5)
js_text.insert(tk.END, "console.log('Hello');")

# Preview button
tk.Button(root, text="Preview", command=preview).grid(row=5, column=0, pady=5)

root.mainloop()
