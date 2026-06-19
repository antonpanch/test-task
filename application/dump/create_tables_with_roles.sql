CREATE TABLE IF NOT EXISTS roles(
    id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(8) NOT NULL,
    CONSTRAINT UNIQUE INDEX unique_index_name (name)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin ENGINE=InnoDb;

CREATE TABLE  IF NOT EXISTS users(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(8) NOT NULL,
    pass VARCHAR(255) NOT NULL,
    phone VARCHAR(8) NOT NULL,
    role_id SMALLINT UNSIGNED NOT NULL,
    CONSTRAINT UNIQUE INDEX unique_index_login_pass (login, pass),
    CONSTRAINT FOREIGN KEY fk_role (role_id) REFERENCES roles (id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin ENGINE=InnoDb;

CREATE TABLE IF NOT EXISTS tokens(
    token VARCHAR(30) PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    expiration_date DATETIME(0),
    INDEX index_user_id (user_id),
    CONSTRAINT FOREIGN KEY fk_user (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT UNIQUE INDEX unique_index_token (token)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin ENGINE=InnoDb;

INSERT INTO roles (id, name) VALUES (1, 'root'), (2, 'user');
