import platform
import psutil
import socket

# Only needed for Windows version detection
import sys
if sys.platform == "win32":
    import winreg

class SystemUtility:
    def __init__(self):
        # Basic system info
        self.os_name = platform.system()
        self.os_version = self.get_windows_version() if self.os_name == "Windows" else platform.version()
        self.processor = platform.processor()
        self.cpu_cores = psutil.cpu_count(logical=True)
        self.memory_total = psutil.virtual_memory().total / (1024 ** 3)  # GB

    # Detect Windows version correctly
    def get_windows_version(self):
        try:
            key = winreg.OpenKey(winreg.HKEY_LOCAL_MACHINE, r"SOFTWARE\Microsoft\Windows NT\CurrentVersion")
            product_name = winreg.QueryValueEx(key, "ProductName")[0]
            return product_name
        except Exception as e:
            return f"Unknown ({e})"

    # Extractor for basic system info
    def get_system_info(self):
        return {
            "Operating System": f"{self.os_name} {self.os_version}",
            "Processor": self.processor,
            "CPU Cores": self.cpu_cores,
            "RAM": f"{self.memory_total:.2f} GB"
        }

    # Extractor for disk usage
    def get_disk_info(self):
        disk = psutil.disk_usage('/')
        return {
            "Total Disk": f"{disk.total / (1024 ** 3):.2f} GB",
            "Used Disk": f"{disk.used / (1024 ** 3):.2f} GB",
            "Free Disk": f"{disk.free / (1024 ** 3):.2f} GB",
            "Disk Usage": f"{disk.percent}%"
        }

    # Extractor for network info
    def get_network_info(self):
        hostname = socket.gethostname()
        ip_address = socket.gethostbyname(hostname)
        return {
            "Hostname": hostname,
            "IP Address": ip_address
        }

    # Extractor for CPU usage
    def get_cpu_usage(self):
        return {
            "CPU Usage": f"{psutil.cpu_percent(interval=1)}%"
        }

    # Display method for any info dictionary
    def display_info(self, info_dict):
        print("=== System Utility Info ===")
        for key, value in info_dict.items():
            print(f"{key}: {value}")
        print("==========================")

# Usage
utility = SystemUtility()

# Display different info
utility.display_info(utility.get_system_info())
utility.display_info(utility.get_disk_info())
utility.display_info(utility.get_cpu_usage())
utility.display_info(utility.get_network_info())
