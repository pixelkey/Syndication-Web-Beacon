This is a small web app to create a tracking pixel / Web beacon.
The web beacon is used to track user interaction of republished articles on third-party websites.

The reason for this:
Articles created by their original publishers are often scraped or intentially re-published on third-party sites by approved syndicators.
The idea is for our third-party syndicators to allow the original publishers to know where content is being published to and how many visits to these articles third-parties receive.

**Initial Setup**

When setting up the project for the first time, create the table in the database with the below definition:
```sql
create table if not exists visits
(
    id         int auto_increment primary key,
    post_id    varchar(128)  null,
    title      text          null,
    visit_date timestamp     null,
    site       text          null,
    url        text          null,
    user_ip    varchar(46)   null,
    user_agent text          null,
    country    varchar(256)  null,
    state      varchar(256)  null,
    city       varchar(256)  null
) DEFAULT COLLATE=utf8mb4_unicode_ci;
```
