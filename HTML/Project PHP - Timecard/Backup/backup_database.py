import os
import subprocess
import re
from datetime import datetime

# Path to your PHP config file
CONFIG_FILE = "../config/config.php"  # Adjust path as needed

# Backup configuration
BACKUP_DIR = "database_backups"
MYSQLDUMP_PATH = "mysqldump"  # Change if mysqldump is not in PATH

# Optional: Keep only the last N backups
MAX_BACKUPS = 10


def read_php_config():
    """Read database credentials from PHP config file"""
    try:
        if not os.path.exists(CONFIG_FILE):
            print(f"✗ Config file not found: {CONFIG_FILE}")
            print("  Please update CONFIG_FILE path in the script")
            return None
        
        with open(CONFIG_FILE, 'r', encoding='utf-8') as f:
            content = f.read()
        
        # Extract variables using regex
        config = {}
        config['host'] = re.search(r'\$host\s*=\s*["\'](.+?)["\']', content)
        config['dbname'] = re.search(r'\$dbname\s*=\s*["\'](.+?)["\']', content)
        config['user'] = re.search(r'\$user\s*=\s*["\'](.+?)["\']', content)
        config['pass'] = re.search(r'\$pass\s*=\s*["\'](.+?)["\']', content)
        
        # Check if all variables were found
        if all(config.values()):
            return {
                'host': config['host'].group(1),
                'dbname': config['dbname'].group(1),
                'user': config['user'].group(1),
                'pass': config['pass'].group(1)
            }
        else:
            print("✗ Could not parse all database credentials from config file")
            return None
            
    except Exception as e:
        print(f"✗ Error reading config file: {str(e)}")
        return None


def create_backup_directory():
    """Create backup directory if it doesn't exist"""
    if not os.path.exists(BACKUP_DIR):
        os.makedirs(BACKUP_DIR)
        print(f"✓ Created backup directory: {BACKUP_DIR}")


def generate_backup_filename(db_name):
    """Generate a timestamped backup filename"""
    timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
    return f"{db_name}_backup_{timestamp}.sql"


def backup_database(config):
    """Perform the database backup using mysqldump"""
    try:
        create_backup_directory()
        
        backup_file = os.path.join(BACKUP_DIR, generate_backup_filename(config['dbname']))
        
        # Construct mysqldump command
        dump_cmd = [
            MYSQLDUMP_PATH,
            f"--host={config['host']}",
            f"--user={config['user']}",
            f"--password={config['pass']}",
            "--single-transaction",
            "--routines",
            "--triggers",
            "--events",
            config['dbname']
        ]
        
        print(f"🔄 Starting backup of database '{config['dbname']}'...")
        
        # Execute mysqldump and write to file
        with open(backup_file, 'w', encoding='utf-8') as f:
            result = subprocess.run(
                dump_cmd,
                stdout=f,
                stderr=subprocess.PIPE,
                text=True
            )
        
        if result.returncode == 0:
            file_size = os.path.getsize(backup_file) / (1024 * 1024)  # Size in MB
            print(f"✓ Backup completed successfully!")
            print(f"  File: {backup_file}")
            print(f"  Size: {file_size:.2f} MB")
            
            # Clean old backups
            cleanup_old_backups(config['dbname'])
            
            return True
        else:
            print(f"✗ Backup failed!")
            print(f"  Error: {result.stderr}")
            # Remove incomplete backup file
            if os.path.exists(backup_file):
                os.remove(backup_file)
            return False
            
    except FileNotFoundError:
        print(f"✗ Error: mysqldump not found at '{MYSQLDUMP_PATH}'")
        print("  Make sure MySQL is installed and mysqldump is in your PATH")
        print("  Or update MYSQLDUMP_PATH in the script")
        return False
    except Exception as e:
        print(f"✗ Unexpected error: {str(e)}")
        return False


def cleanup_old_backups(db_name):
    """Keep only the most recent MAX_BACKUPS backup files"""
    try:
        backups = [
            f for f in os.listdir(BACKUP_DIR)
            if f.startswith(f"{db_name}_backup_") and f.endswith(".sql")
        ]
        
        if len(backups) > MAX_BACKUPS:
            # Sort by creation time (oldest first)
            backups.sort(key=lambda x: os.path.getctime(os.path.join(BACKUP_DIR, x)))
            
            # Remove oldest backups
            backups_to_remove = backups[:-MAX_BACKUPS]
            for backup in backups_to_remove:
                file_path = os.path.join(BACKUP_DIR, backup)
                os.remove(file_path)
                print(f"  Cleaned up old backup: {backup}")
            
            print(f"✓ Kept last {MAX_BACKUPS} backups")
    except Exception as e:
        print(f"⚠ Warning: Could not clean up old backups: {str(e)}")


def list_backups(db_name):
    """List all available backups"""
    try:
        if not os.path.exists(BACKUP_DIR):
            print("No backups found.")
            return
        
        backups = [
            f for f in os.listdir(BACKUP_DIR)
            if f.startswith(f"{db_name}_backup_") and f.endswith(".sql")
        ]
        
        if not backups:
            print("No backups found.")
            return
        
        print(f"\n📋 Available backups ({len(backups)}):")
        print("-" * 70)
        
        # Sort by creation time (newest first)
        backups.sort(key=lambda x: os.path.getctime(os.path.join(BACKUP_DIR, x)), reverse=True)
        
        for backup in backups:
            file_path = os.path.join(BACKUP_DIR, backup)
            file_size = os.path.getsize(file_path) / (1024 * 1024)  # MB
            file_time = datetime.fromtimestamp(os.path.getctime(file_path))
            print(f"  {backup}")
            print(f"    Size: {file_size:.2f} MB | Created: {file_time.strftime('%Y-%m-%d %H:%M:%S')}")
        print("-" * 70)
    except Exception as e:
        print(f"✗ Error listing backups: {str(e)}")


def restore_database(config, backup_file):
    """Restore database from a backup file"""
    try:
        backup_path = os.path.join(BACKUP_DIR, backup_file)
        
        if not os.path.exists(backup_path):
            print(f"✗ Backup file not found: {backup_file}")
            return False
        
        print(f"⚠ WARNING: This will overwrite the current database!")
        confirm = input(f"Are you sure you want to restore '{backup_file}'? (yes/no): ")
        
        if confirm.lower() != 'yes':
            print("Restore cancelled.")
            return False
        
        print(f"🔄 Restoring database from {backup_file}...")
        
        # Construct mysql command
        restore_cmd = [
            "mysql",
            f"--host={config['host']}",
            f"--user={config['user']}",
            f"--password={config['pass']}",
            config['dbname']
        ]
        
        # Execute mysql restore
        with open(backup_path, 'r', encoding='utf-8') as f:
            result = subprocess.run(
                restore_cmd,
                stdin=f,
                stderr=subprocess.PIPE,
                text=True
            )
        
        if result.returncode == 0:
            print(f"✓ Database restored successfully from {backup_file}!")
            return True
        else:
            print(f"✗ Restore failed!")
            print(f"  Error: {result.stderr}")
            return False
            
    except Exception as e:
        print(f"✗ Unexpected error during restore: {str(e)}")
        return False


def main():
    """Main function with menu"""
    print("=" * 70)
    print("  DATABASE BACKUP UTILITY")
    print("=" * 70)
    
    # Read config from PHP file
    config = read_php_config()
    if not config:
        print("\n✗ Failed to load database configuration. Exiting.")
        return
    
    print(f"  Database: {config['dbname']}")
    print(f"  Host: {config['host']}")
    print(f"  User: {config['user']}")
    print(f"  Backup Directory: {BACKUP_DIR}")
    print("=" * 70)
    print("\nOptions:")
    print("  1. Create new backup")
    print("  2. List all backups")
    print("  3. Restore from backup")
    print("  4. Exit")
    print()
    
    choice = input("Enter your choice (1-4): ").strip()
    
    if choice == "1":
        print()
        backup_database(config)
    elif choice == "2":
        list_backups(config['dbname'])
    elif choice == "3":
        print()
        list_backups(config['dbname'])
        print()
        backup_file = input("Enter backup filename to restore: ").strip()
        restore_database(config, backup_file)
    elif choice == "4":
        print("Goodbye!")
        return
    else:
        print("Invalid choice!")


if __name__ == "__main__":
    main()