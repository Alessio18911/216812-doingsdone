-- Заполнение таблицы "Users"
INSERT INTO users (name, password, email) VALUES('Глупый Король', '12345', 'glup.korol@korolevstvo.bm');
INSERT INTO users (name, password, email) VALUES('Трубадур', '12378', 'trubadur@muzikanty.bm');
INSERT INTO users(name, password, email) VALUES('Атаманша', '45665', 'atamansha@bandit.bm');
INSERT INTO users(name, password, email) VALUES('Гениальный Сыщик', '112233', 'clever.detective@korolevstvo.bm');
INSERT INTO users(name, password, email) VALUES('Принцесса', '11654', 'princess@korolevstvo.bm');

-- Заполнение таблицы "Projects"
INSERT INTO projects(title, user_id) VALUES('Королевство', '1');
INSERT INTO projects(title, user_id) VALUES('Воспитание принцессы', '1');
INSERT INTO projects(title, user_id) VALUES('Слежка за принцессой', '1');
INSERT INTO projects(title, user_id) VALUES('Развлечение', '1');
INSERT INTO projects(title, user_id) VALUES('Работа', '2');
INSERT INTO projects(title, user_id) VALUES('Любовь', '2');
INSERT INTO projects(title, user_id) VALUES('Повседневное существование', '3');
INSERT INTO projects(title, user_id) VALUES('Разбой', '3');
INSERT INTO projects(title, user_id) VALUES('Пир', '3');
INSERT INTO projects(title, user_id) VALUES('Расследования', '4');
INSERT INTO projects(title, user_id) VALUES('Моральная поддержка короля', '4');
INSERT INTO projects(title, user_id) VALUES('Любовь', '5');
INSERT INTO projects(title, user_id) VALUES('Капризы', '5');

-- Заполнение таблицы "Tasks"
INSERT INTO tasks(user_id, project_id, name) VALUES('1', '1', 'Осчастливить подданых');
INSERT INTO tasks(user_id, project_id, name) VALUES('1', '1', 'Наказать нерадивых слуг');
INSERT INTO tasks(user_id, project_id, name) VALUES('1', '2', 'Нанять учителя музыки');
INSERT INTO tasks(user_id, project_id, name) VALUES('1', '2', 'Купить клавесин');
INSERT INTO tasks(user_id, project_id, name) VALUES('1', '2', 'Проконтролировать уроки');
INSERT INTO tasks(user_id, project_id, name) VALUES('1', '3', 'Найти Гениального сыщика');
INSERT INTO tasks(user_id, project_id, name) VALUES('1', '4', 'Проводить балы каждую неделю');
INSERT INTO tasks(user_id, project_id, name) VALUES('1', '4', 'Выезжать в карете с охранниками ежедневно');
INSERT INTO tasks(user_id, project_id, name) VALUES('1', '4', 'Пригласить заграничных певцов');

INSERT INTO tasks(user_id, project_id, name) VALUES('2', '5', 'Искать площадки для выступлений');
INSERT INTO tasks(user_id, project_id, name) VALUES('2', '5', 'Выступать с друзьями');
INSERT INTO tasks(user_id, project_id, name) VALUES('2', '6', 'Воздыхать о принцессе');
INSERT INTO tasks(user_id, project_id, name) VALUES('2', '6', 'Петь серенады принцессе');

INSERT INTO tasks(user_id, project_id, name) VALUES('3', '7', 'Погадать на короля');
INSERT INTO tasks(user_id, project_id, name) VALUES('3', '7', 'Украсть еды');
INSERT INTO tasks(user_id, project_id, name) VALUES('3', '7', 'Привести в чувство разбойников после вчерашнего пира');
INSERT INTO tasks(user_id, project_id, name) VALUES('3', '8', 'Перебить весь отряд короля');
INSERT INTO tasks(user_id, project_id, name) VALUES('3', '8', 'Ограбить короля');
INSERT INTO tasks(user_id, project_id, name) VALUES('3', '8', 'Взять короля в плен');
INSERT INTO tasks(user_id, project_id, name) VALUES('3', '9', 'Пить ром, есть до упаду, петь во всё горло, танцевать на бочке');

INSERT INTO tasks(user_id, project_id, name) VALUES('4', '10', 'Найти прыщик на тебе у слона');
INSERT INTO tasks(user_id, project_id, name) VALUES('4', '10', 'Всех разоблачить');
INSERT INTO tasks(user_id, project_id, name) VALUES('4', '10', 'Погоня за бременскими музыкантами');
INSERT INTO tasks(user_id, project_id, name) VALUES('4', '11', 'Всячески поддерживать короля, когда ему грустно');
INSERT INTO tasks(user_id, project_id, name) VALUES('4', '11', 'Вытереть сопли королю, когда он плачет');
