from smartcard.System import readers
from smartcard.Exceptions import NoCardException, CardConnectionException

DEFAULT_KEY = [0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF] 

def clean_data(data):
    """
    Clean the data by removing non-printable characters and keeping only digits.
    """
    cleaned_numeric_data = ''.join([chr(byte) for byte in data if 48 <= byte <= 57])
    return cleaned_numeric_data

def read_card_data(start_block, num_blocks, expected_length=8):
    """
    Reads data from the card starting from `start_block` for `num_blocks`.
    """
    try:
        r = readers()
        if len(r) == 0:
            print("No readers available")
            return None

        reader = r[0] 
        connection = reader.createConnection()

        try:
            connection.connect()
        except NoCardException:
            print("No card present. Please place the card on the reader.")
            return None

        full_data = ""
        for block in range(start_block, start_block + num_blocks):
            AUTHENTICATE = [0xFF, 0x86, 0x00, 0x00, 0x05, 0x01, 0x00, block, 0x60, 0x00]  
            connection.transmit(AUTHENTICATE + DEFAULT_KAEY)

            READ_BLOCK = [0xFF, 0xB0, 0x00, block, 0x10] 
            data, sw1, sw2 = connection.transmit(READ_BLOCK)

            if sw1 == 0x90 and sw2 == 0x00:
                cleaned_data = clean_data(data)
                
                if cleaned_data.isdigit() and len(cleaned_data) >= expected_length:
                    if not full_data: 
                        full_data = cleaned_data
            
            else:
                print(f"Error reading block {block}: SW1={sw1}, SW2={sw2}")
                return None

        return full_data if full_data else None

    except CardConnectionException as e:
        print(f"Card removed or communication error: {e}")
        return None
    except Exception as e:
        print(f"An error occurred: {e}")
        return None

if __name__ == "__main__":
    start_block = 4  
    num_blocks = 4   
    expected_length = 8  
    card_data = read_card_data(start_block, num_blocks, expected_length)
    
    if card_data:
        last_eight_characters = card_data[-8:] 
        print(f"{last_eight_characters}")
    else:
        print("Failed to read card data.")
