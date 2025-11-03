
import time  # <-- add this at the top
item_list = {}

def add_item():
    item = input("Item name: ")
    price = float(input("Price: "))
    qty = int(input("Qty: "))
    item_list[item] = {"price": price, "qty": qty}
    print(f"{item} added to the list!")
    time.sleep(2)  # pause for 2 seconds

def view_items():
    if not item_list:
        print("No items in the list.")
        return
    for name, data in item_list.items():
        print(f"Item: {name} | Price: {data['price']} | Qty: {data['qty']} | Total: {data['price']*data['qty']}")
    time.sleep(2)  # pause for 2 seconds

def delete_item():
    item = input("Enter the item name to delete: ")
    if item in item_list:
        del item_list[item]
        print(f"{item} deleted.")
    else:
        print(f"{item} not found.")
    time.sleep(2)  # pause for 2 seconds

# Menu loop
user_input = ""
while user_input != "q":
    print("1. Add item, price, qty")
    print("2. View item, price, qty")
    print("3. Delete item, price, qty")
    print("(q) Quit")

    user_input = input("Select from menu (q) to quit: ").lower()

    if user_input == "1":
        add_item()
    elif user_input == "2":
        view_items()
    elif user_input == "3":
        delete_item()
    elif user_input != "q":
        print("Invalid option!")
    time.sleep(2)  # pause for 2 seconds

print("Goodbye")