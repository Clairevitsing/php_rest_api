CREATE TABLE users(
        id  INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
        name  VARCHAR (50) NOT NULL ,
        password VARCHAR(50) NOT NULL,
        registerTime timestamp NOT NULL
)ENGINE=InnoDB;

CREATE TABLE articles(
        id  INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
        title   Varchar (250) NOT NULL ,
        content Text(500) NOT NULL ,
        user_id int (11) NOT NULL,
        createTime timestamp NOT NULL,
	    FOREIGN KEY (user_id) REFERENCES users(id)     
)ENGINE=InnoDB;

INSERT INTO users (id, name, password, registerTime) VALUES 
	(1, 'claire', '123456', '2022-04-11'),
    (2, 'zhou', '456789', '2022-05-23'),
    (3, 'zhao', '678900', '2021-06-28');

INSERT INTO articles (id, title, content, createTime, user_id) VALUES 
	(1, ' How To Change Your Life For The Better', 
    'One of the best ways to get new readers is to identify and solve a problem with trigger words.', '2023-12-16', '2'),
    (2, '6 Instant Confidence Boosters', 
    'Sometimes we get writer’s block and can’t think of a clever headline… 
    This is where making a direct statement is the easiest and most effective way to engage your reader. ', '2022-05-23','3'),
    (3, 'Which One Deserves To Die', 'It’s a very effective tool for generating attention. 
    Just try not to land on the wrong side of an issue or be disrespectful.', '2022-06-29','1');