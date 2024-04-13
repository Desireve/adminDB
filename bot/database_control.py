import sqlite3
path_db = "./bot_database.db"

def create_db():
    try:
        cursor_wtfname.execute('''CREATE TABLE users(
                                id integer PRIMARY KEY,
                                tgid integer NOT NULL UNIQUE,
                                username text NOT NULL,
                               datetime DATETIME NOT NULL
                                )''')
    except sqlite3.Error as e:
        print(e)


async def add_book_to_request_list_sql(chat_id, msg_id, datetime):
    try:
        cursor_wtfname.execute(f'INSERT INTO books_request (tg_chat_id, tg_msg_id, datetime) VALUES ({chat_id},"{msg_id}", "{datetime}")')
        connect_db.commit()
    except sqlite3.Error as e:
        print(e)

async def add_in_users(tgid:int, username:str, datetime):
    try:
        cursor_wtfname.execute(f'INSERT INTO users (tgid, username, datetime) VALUES ({tgid},"{username}", "{datetime}")')
        connect_db.commit()
    except sqlite3.Error as e:
        print(e)


def pervichnaya_add_money_for_all_users_in_money():
    # получаем все записи из таблицы users
    cursor_wtfname.execute("SELECT tgid FROM users")
    users = cursor_wtfname.fetchall()
    # для каждой записи из таблицы users
    print(users)
    for tgid in users:
        print(tgid[0])
        # добавляем запись в таблицу money с id, m_id и balance
        cursor_wtfname.execute("INSERT INTO money (tgid, balance) VALUES (?, ?)", (tgid[0], 0))
    # сохраняем изменения в базе данных
    connect_db.commit()

try:    
    connect_db = sqlite3.connect(path_db)
    cursor_wtfname = connect_db.cursor()
except sqlite3.Error as e:
    print(e)


def get_my_balance(tgid):
    cursor_wtfname.execute(f"SELECT balance FROM money WHERE {tgid}")
    balance = cursor_wtfname.fetchall()
    
    return balance[0][0]

def add_row_in_musicname_db(name, path):
    try:
        cursor_wtfname.execute(f'INSERT INTO music (name, path) VALUES ("{name}","{path}")')
        connect_db.commit()
    except sqlite3.Error as e:
        print(e)

def get_row_from_musicname_in_db(name):
    rows = cursor_wtfname.execute(f'SELECT * FROM music WHERE name = "{name}"').fetchall()
    print(rows)

def add_row_in_music_history(id, tgid, link, musname):
    try:
        cursor_wtfname.execute(f'INSERT INTO history (id, tgid, link, music_name) VALUES ({id},{tgid},"{link}", "{musname}")')
        connect_db.commit()
    except sqlite3.Error as e:
        print(e)
    


