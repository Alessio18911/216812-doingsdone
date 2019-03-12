-- Заполнение таблицы "Users"
INSERT INTO users (name, password, email) VALUES('Глупый Король', '12345', 'glup.korol@korolevstvo.bm');
INSERT INTO users (name, password, email) VALUES('Трубадур', '12378', 'trubadur@muzikanty.bm');
INSERT INTO users(name, password, email) VALUES('Атаманша', '45665', 'atamansha@bandit.bm');
INSERT INTO users(name, password, email) VALUES('Гениальный Сыщик', '112233', 'clever.detective@korolevstvo.bm');
INSERT INTO users(name, password, email) VALUES('Принцесса', '11654', 'princess@korolevstvo.bm');

-- Заполнение таблицы "Projects"
INSERT INTO categories(name, user_id) VALUES('Королевство', '1');
INSERT INTO categories(name, user_id) VALUES('Воспитание принцессы', '1');
INSERT INTO categories(name, user_id) VALUES('Слежка за принцессой', '1');
INSERT INTO categories(name, user_id) VALUES('Развлечение', '1');
INSERT INTO categories(name, user_id) VALUES('Работа', '2');
INSERT INTO categories(name, user_id) VALUES('Любовь', '2');
INSERT INTO categories(name, user_id) VALUES('Повседневное существование', '3');
INSERT INTO categories(name, user_id) VALUES('Разбой', '3');
INSERT INTO categories(name, user_id) VALUES('Пир', '3');
INSERT INTO categories(name, user_id) VALUES('Расследования', '4');
INSERT INTO categories(name, user_id) VALUES('Моральная поддержка короля', '4');
INSERT INTO categories(name, user_id) VALUES('Любовь', '5');
INSERT INTO categories(name, user_id) VALUES('Капризы', '5');

-- Заполнение таблицы "Tasks"
INSERT INTO tasks(user_id, category_id, name, expires_at) VALUES('1', '1', 'Осчастливить подданых', NULL);
INSERT INTO tasks(user_id, category_id, name, expires_at) VALUES('1', '1', 'Наказать нерадивых слуг', '2019-02-20');
INSERT INTO tasks(user_id, category_id, name, expires_at) VALUES('1', '2', 'Нанять учителя музыки', '2019-03-01');
INSERT INTO tasks(user_id, category_id, name, expires_at) VALUES('1', '2', 'Купить клавесин', '2019-03-01');
INSERT INTO tasks(user_id, category_id, name, expires_at) VALUES('1', '2', 'Проконтролировать уроки', NULL);
INSERT INTO tasks(user_id, category_id, name, expires_at) VALUES('1', '4', 'Пригласить заграничных певцов', NULL);
INSERT INTO tasks(user_id, category_id, name, expires_at) VALUES('1', '4', 'Проводить балы каждую неделю', NULL);
INSERT INTO tasks(user_id, category_id, name, expires_at) VALUES('1', '4', 'Выезжать в карете с охранниками ежедневно', NULL);

INSERT INTO tasks(user_id, category_id, name, expires_at) VALUES('2', '5', 'Искать площадки для выступлений', NULL);
INSERT INTO tasks(user_id, category_id, name, expires_at) VALUES('2', '5', 'Выступать с друзьями', '2019-02-25');
INSERT INTO tasks(user_id, category_id, name, expires_at) VALUES('2', '6', 'Воздыхать о принцессе', NULL);
INSERT INTO tasks(user_id, category_id, name, expires_at) VALUES('2', '6', 'Петь серенады принцессе', NULL);

INSERT INTO tasks(user_id, category_id, name, expires_at) VALUES('3', '7', 'Погадать на короля', '2019-04-01');
INSERT INTO tasks(user_id, category_id, name, expires_at) VALUES('3', '7', 'Украсть еды', NULL);
INSERT INTO tasks(user_id, category_id, name, expires_at) VALUES('3', '7', 'Привести в чувство разбойников после вчерашнего пира', NULL);
INSERT INTO tasks(user_id, category_id, name, expires_at) VALUES('3', '8', 'Перебить весь отряд короля', '2019-04-02');
INSERT INTO tasks(user_id, category_id, name, expires_at) VALUES('3', '8', 'Ограбить короля', '2019-04-02');
INSERT INTO tasks(user_id, category_id, name, expires_at) VALUES('3', '8', 'Взять короля в плен', '2019-04-02');
INSERT INTO tasks(user_id, category_id, name, expires_at) VALUES('3', '9', 'Пить ром, есть до упаду, петь во всё горло, танцевать на бочке', NULL);

INSERT INTO tasks(user_id, category_id, name, expires_at) VALUES('4', '10', 'Найти прыщик на теле у слона', NULL);
INSERT INTO tasks(user_id, category_id, name, expires_at) VALUES('4', '10', 'Всех разоблачить', NULL);
INSERT INTO tasks(user_id, category_id, name, expires_at) VALUES('4', '10', 'Погоня за бременскими музыкантами', '2019-03-08');
INSERT INTO tasks(user_id, category_id, name, expires_at) VALUES('4', '11', 'Всячески поддерживать короля, когда ему грустно', NULL);
INSERT INTO tasks(user_id, category_id, name, expires_at) VALUES('4', '11', 'Вытереть сопли королю, когда он плачет', NULL);

INSERT INTO tasks(user_id, category_id, name, expires_at) VALUES('5', '12', 'Сидеть и грустить у окна', NULL);
INSERT INTO tasks(user_id, category_id, name, expires_at) VALUES('5', '12', 'Мечтать о нём', NULL);
INSERT INTO tasks(user_id, category_id, name, expires_at) VALUES('5', '12', 'Ничего не хотеть', NULL);
INSERT INTO tasks(user_id, category_id, name, expires_at) VALUES('5', '13', 'Выкидывать фортели', NULL);

-- Запросы по заданию
SELECT users.name, categories.name FROM users
	JOIN categories
	ON users.id = categories.user_id
	WHERE users.id = 1;

SELECT categories.name, tasks.name FROM categories
	JOIN tasks
	ON categories.id = tasks.category_id
	WHERE categories.name = 'Любовь';

UPDATE tasks SET status = 1 WHERE id = 6;

UPDATE tasks SET name = 'Отбить пальцы папеньке крышкой клавесина', expires_at = "2019-03-01" WHERE id = 28;

CREATE FULLTEXT INDEX tasks_ft_search ON tasks(name);
