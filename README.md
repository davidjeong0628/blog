# Personal Blog

Created a mock personal blog website using PHP, MySQL, Bootstrap, HTML, and CSS.

Implemented:

1) User login and registration with server-side validation and salted password hashing.

2) Creating, updating, and deleting posts for users with administrative privilege.

3) Articles with public/private access.

4) Database that stores user and blog posts data.

5) List of articles with pagination.

etc...

### Database

**2** tables: users and articles

**users Structure**:

user_id (PRIMARY KEY), email (varchar), username (varchar), pw (char), privilege(char)

**articles Structure**

article_id (PRIMARY KEY), title (varchar), pub_date (date), edit_date (date), entry_text (mediumtext), access (char)
