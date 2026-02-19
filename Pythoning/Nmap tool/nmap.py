import shutil
import subprocess
import socket
import ctypes

def check_nmap():
    return shutil.which("nmap") is not None

def install_nmap():
    print("Installing Nmap...")
    subprocess.run(
        ["winget", "install", "--id", "Nmap.Nmap", "-e",
         "--accept-source-agreements",
         "--accept-package-agreements"],
        check=True
    )

def is_admin():
    return ctypes.windll.shell32.IsUserAnAdmin() != 0

def get_local_ip():
    s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    try:
        s.connect(("8.8.8.8", 80))
        ip = s.getsockname()[0]
    finally:
        s.close()
    return ip

def get_subnet(ip):
    return ip.rsplit('.', 1)[0] + ".0/24"

def scan_network(subnet):
    print("Scanning network...")
    result = subprocess.run(
        ["nmap", "-O", "-F", subnet],
        capture_output=True,
        text=True
    )
    return result.stdout

def parse_os(output):
    current_ip = None
    for line in output.splitlines():
        if "Nmap scan report for" in line:
            current_ip = line.split()[-1]
        if "OS details:" in line and current_ip:
            os_info = line.replace("OS details: ", "")
            print(f"{current_ip} -> {os_info}")

if not is_admin():
    print("Please run as Administrator for OS detection.")

if not check_nmap():
    install_nmap()

ip = get_local_ip()
subnet = get_subnet(ip)

output = scan_network(subnet)
parse_os(output)
