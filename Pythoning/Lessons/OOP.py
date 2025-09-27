
#https://www.youtube.com/watch?v=Ej_02ICOIgs 31min mark

#item1 = 'Phone'
#item1_price = 100
#item1_quantity = 5
#item1_price_total = item1_price * item1_quantity

#print(type(item1)) # str
#print(type(item1_price)) #int
#print(type(item1_quantity)) #int
#print(type(item1_price_total)) #int

class Item:
    def __init__(self, name: str, price: float, quantity=0):
        #print(f"instance created for: {name}") #runs as intialization and prints text
        #run validations to the received arguments
        assert price >= 0, f"Price {price} is not greater than zero"
        assert quantity >= 0, f"Quantity {quantity} is not greater than zero"
        
        #Assign to self object
        self.name = name
        self.price = price
        self.quantity = quantity 
        

    def calculate_total_price(self):
        return self.price * self.quantity


item1 = Item("Phone", 100, 5)
#item1.name = "Phone"
#item1.price = 100
#item1.quantity = 5
#print(item1.calculate_total_price(item1.price, item1.quantity))

item2 = Item("Laptop", 1000, -3)
#item2.name = "Laptop"
#item2.price = 1000
#item2.quantity = 3
#print(item2.calculate_total_price(item2.price, item2.quantity))


item3 = Item("Tablet", 300)

print(item1.name)
print(item2.name)
print(item3.name)
print(item1.price)
print(item2.price)
print(item3.price)
print(item1.quantity)
print(item2.quantity)
print(item3.quantity)
print(item1.calculate_total_price())
print(item2.calculate_total_price())