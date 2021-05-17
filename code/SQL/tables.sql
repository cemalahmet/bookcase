SET FOREIGN_KEY_CHECKS = 0;

DROP 
  TABLE IF EXISTS part_of_series;
DROP 
  TABLE IF EXISTS bookshelf_includes;
DROP 
  TABLE IF EXISTS bookshelves;
DROP 
  TABLE IF EXISTS series;
DROP 
  TABLE IF EXISTS progress;
DROP 
  TABLE IF EXISTS publishes;
DROP 
  TABLE IF EXISTS publishers;
DROP 
  TABLE IF EXISTS editions;
DROP 
  TABLE IF EXISTS reply;
DROP 
  TABLE IF EXISTS writes;
DROP 
  TABLE IF EXISTS recommends;
DROP 
  TABLE IF EXISTS likes;
DROP 
  TABLE IF EXISTS comment_on;
DROP 
  TABLE IF EXISTS comments;
DROP 
  TABLE IF EXISTS list_includes;
DROP 
  TABLE IF EXISTS lists;
DROP 
  TABLE IF EXISTS rate;
DROP 
  TABLE IF EXISTS book_genre;
DROP 
  TABLE IF EXISTS books;
DROP 
  TABLE IF EXISTS author_data;
DROP 
  TABLE IF EXISTS follows;
DROP 
  TABLE IF EXISTS author_genre;
DROP 
  TABLE IF EXISTS authors;
DROP 
  TABLE IF EXISTS author_accounts;
DROP 
  TABLE IF EXISTS friends;
DROP 
  TABLE IF EXISTS joins;
DROP 
  TABLE IF EXISTS challenges;
DROP 
  TABLE IF EXISTS librarians;
DROP 
  TABLE IF EXISTS users;  
DROP 
  TABLE IF EXISTS accounts;

SET FOREIGN_KEY_CHECKS = 1;


create table accounts(
  account_id int not null auto_increment, 
  username varchar(15) not null, 
  e_mail varchar(30) not null, 
  password varchar(10) not null, 
  primary key (account_id)
);
create table users(
  user_id int not null, 
  p_picture BLOB, 
  join_date date not null, 
  primary key (user_id), 
  foreign key(user_id) references accounts(account_id)
);
create table librarians(
  librarian_id int not null, 
  primary key (librarian_id), 
  foreign key(librarian_id) references accounts(account_id)
);
create table challenges(
  challange_name varchar(32) not null, 
  librarian_id int not null, 
  Info varchar(100) not null, 
  due_date date not null, 
  primary key (challange_name), 
  foreign key(librarian_id) references librarians(librarian_id)
);
create table joins(
  challange_name varchar(32) not null, 
  user_id int not null, 
  goal int not null, 
  primary key (challange_name, user_id), 
  foreign key(challange_name) references challenges(challange_name),
  foreign key(user_id) references users(user_id)
);
create table friends(
  user_id int not null, 
  friend_id int not null, 
  primary key (user_id, friend_id), 
  foreign key(user_id) references users(user_id),
  foreign key(friend_id) references users(user_id)
);
create table author_accounts(
  author_id int not null, 
  primary key (author_id), 
  foreign key(author_id) references users(user_id) 
  );
create table authors(
  author_id int not null auto_increment, 
  author_name varchar(15) not null, 
  biography varchar(1000) not null, 
  primary key (author_id)
);
create table author_genre(
  author_id int not null, 
  genre varchar(20) not null, 
  foreign key (author_id) references authors(author_id),
  primary key (author_id, genre)
);
create table follows(
  user_id int not null, 
  author_id int not null, 
  primary key (user_id, author_id), 
  foreign key(user_id) references users(user_id) ,
  foreign key(author_id) references authors(author_id)
);
create table author_data(
  account_id int not null, 
  author_id int not null, 
  primary key (account_id), 
  foreign key(account_id) references author_accounts(author_id), 
  foreign key(author_id) references authors(author_id)
);
create table books(
  b_id int not null auto_increment, 
  cover BLOB, 
  title varchar(100) not null, 
  year YEAR not null, 
  primary key (b_id)
);
create table book_genre(
  b_id int not null, 
  genre varchar(20) not null, 
  foreign key (b_id) references books(b_id),
  primary key (b_id, genre)
);
create table rate(
  b_id int not null, 
  user_id int not null, 
  rating int not null, 
  foreign key (b_id) references books(b_id),
  foreign key (user_id) references users(user_id),
  primary key (b_id, user_id)
);
create table lists(
  list_name varchar(20) not null, 
  user_id int not null, 
  primary key (list_name, user_id), 
  foreign key(user_id) references users(user_id) on delete cascade
);
create table list_includes(
  list_name varchar(20) not null, 
  user_id int not null, 
  b_id int not null, 
  primary key (list_name, user_id, b_id), 
  foreign key(b_id) references books(b_id), 
  foreign key(list_name, user_id) references lists(list_name, user_id)
);
create table comments(
  comment_id int not null auto_increment, 
  comment varchar(500) not null, 
  date date not null, 
  primary key (comment_id)
);
create table comment_on(
  b_id int not null, 
  comment_id int not null, 
  user_id int not null, 
  primary key (b_id, comment_id), 
  foreign key(b_id) references books(b_id), 
  foreign key(comment_id) references comments(comment_id), 
  foreign key(user_id) references users(user_id)
);
create table likes(
  user_id int not null, 
  comment_id int not null, 
  primary key (user_id, comment_id), 
  foreign key(user_id) references users(user_id), 
  foreign key(comment_id) references comments(comment_id)
);
create table recommends(
  user_id int not null, 
  friend_id int not null, 
  b_id int not null, 
  primary key (user_id, friend_id, b_id), 
  foreign key(user_id) references users(user_id), 
  foreign key(friend_id) references users(user_id), 
  foreign key(b_id) references books(b_id)
  );
create table writes(
  author_id int not null, 
  b_id int not null, 
  primary key (author_id, b_id), 
  foreign key(author_id) references authors(author_id), 
  foreign key(b_id) references books(b_id) 
  );
create table reply(
  reply_id int not null, 
  comment_id int not null, 
  user_id int not null, 
  primary key (reply_id, user_id), 
  foreign key(reply_id) references comments(comment_id), 
  foreign key(comment_id) references comments(comment_id), 
  foreign key(user_id) references users(user_id) 
  );
create table editions(
  b_id int not null, 
  edition_no int not null, 
  page_count int not null, 
  language varchar(10) not null, 
  translator_name varchar(15), 
  primary key (b_id, edition_no), 
  foreign key(b_id) references books(b_id) ON DELETE CASCADE
);
create table publishers(
  p_id int not null auto_increment, 
  p_name varchar(30) not null, 
  city varchar(30) not null, 
  primary key (p_id)
);
create table publishes(
  p_id int not null, 
  b_id int not null, 
  edition_no int not null, 
  publish_year YEAR not null, 
  primary key (p_id, b_id, edition_no),
  foreign key (p_id) references publishers(p_id),
  foreign key (b_id, edition_no) references editions(b_id, edition_no)
);
create table progress(
  user_id int not null, 
  b_id int not null, 
  edition_no int not null, 
  page_no int not null, 
  primary key (user_id, b_id,edition_no), 
  foreign key(user_id) references users(user_id), 
  foreign key(b_id,edition_no) references editions(b_id,edition_no) 
  );
create table series(
  s_name varchar(30) not null, 
  primary key (s_name)
);
create table bookshelves(
  bs_name varchar(20) not null, 
  user_id int not null, 
  primary key (bs_name, user_id), 
  foreign key(user_id) references users(user_id) on delete cascade
);
create table bookshelf_includes(
  bs_name varchar(20) not null, 
  user_id int not null, 
  b_id int not null, 
  primary key (bs_name, user_id, b_id),
  foreign key(b_id) references books(b_id), 
  foreign key(user_id, bs_name) references bookshelves(user_id, bs_name)
);
create table part_of_series(
  s_name varchar(20) not null, 
  b_id int not null, 
  primary key (s_name, b_id), 
  foreign key(s_name) references series(s_name), 
  foreign key(b_id) references books(b_id)
);
