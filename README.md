# HSF Community Post
## Excuses, Excuses!
This was quite a big tech test and with a limited amount of time, I have got it as far along as I can get it.  There are aspects that are missing from this technical test, but I hope there is enough here to give you an idea for how I work.   There are certain elements that I would do better if I had the time to do so, however it's almost 1 AM and I promised it by tomorrow.   I have opted to down tools here.


## How to run the project
- Clone the repo down to your local machine in a place you'll be able to find it easily.
- Navigate to the folder and copy your `env.example` to `env`.  I have done my best to make sure you don't need to make any changes to the example file for this to run, but depending on your current system and ports used, you may need to adjust.
- Open Windows PowerShell / iOS Terminal / Your favourite CLI tool and navigate to that folder.
- Double check that `Docker Desktop` or equivalent is running on your machine.  Open it, if not.
- Run `docker-compose exec hfs_community_api composer install` to install the required composer files.
- Run `docker-compose exec hfs_community_api key:generate` to generate the project key.
- Run `docker-compose exec hfs_community_api migrate` to migrate your database.  Note that this may fail if the env file needs adjusting.
- Run `docker-compose exec hfs_community_api db:seed` to seed the database.  This will set up the front end with a random example for how it will look.  Without seeding, you wont at this stage be able to add data to the database.
- Change directory to the client directory.
- Run `npm install` to install required npm packages.
- Run `npm run dev` to launch your front end version.
- If your front end is not running on `http://localhost:5173/` then go to the api folder and edit `Config\FrontEnd.php` and change the url there to match.


### Task Tracker

| Task | Back End | Front End | Notes |
|------|:---------|:---------:|-------|
| Login / Logout | - [x] | - [x]  |      |
| Profile Management | - [ ] | - [ ] | Opted out of this one as I had already demonstrated adding comments and similar with form requests, validation, etc - examples already exist  |
| Password Recovery | - [x] | - [x] | You may need to adjust the config file (frontend) if you change the ports, otherwise the email won't redirect to the front end.  See mailhog to view email |
| Users can submit a new article | - [ ] | - [x] | I didn't have time to implement this on the front end, but the back end exists, with feature tests on the success case. |
| Users can edit or delete their own articles | - [ ] | - [x] | I didn't have time to implement this on the front end, but the back end exists, with feature tests on the success case. |
| Submitted articles must include a title and description | - [x] | - [x] | I've called this 'title' and 'content', but in effect, the same thing |
| Users can submit comments on articles. | - [x] | - [ ] | I didn't have time to implement this on the front end, but the back end exists, with feature tests on the success case. |
| Nested Comments | - [x] | - [x] | The front end here is not ideal.  If I had more time, I would use recursive templates or similar to display the nested comments. |
| Deleting own comments | - [x] | - [ ] | I didn't have time to implement this on the front end, but the back end exists, with feature tests on the success case. |
| Upvote / Downvote functionality | - [x] | - [x] | If I had more time, I would like to lock it so that a user can't vote more than once.  Presently, they can, but the database has the structure there to prevent it. |
| Ability to sort articles by page votes | - [x] | - [x] | Used the opportunity to demonstrate working to an interface.  Could have also used Scopes here, or a combination of the two, with more time. |
| Ability to sort articles by new, top | - [x] | - [x] | |
| Ability to sort comments by date | - [ ] | - [ ] | Sadly not one I got around to.  I wanted to present something that at least partly functioned and comments were the last thing I worked on.  I wanted to get a 'complete to milestone' version over the line as I knew I wasn't going to finish it.  An idea for approach would be to follow a similar pattern as I have and pass through a scope / interface which orders on the relationship.  That way all the replies and sub replies should also follow the same scope and each be in asc/desc. |

### Non Technical Notes 

| Non Technical Consideration | Notes |
|-----------------------------|-------|
| The app should load quickly and queries should be optimised | In places I've used raw SQL (notably in subqueries) in order to remove the ORM overhead.  I could further optimise the use of ORM by selecting only the columns I require when pulling in relationships.  A good example of this is 'replies' and 'user'.  My intention was to get a working version, then tidy up afterwards, but I ran out of time here.|
| Protect against common vulnerabilities such as SQL Injection, XSS, CSRF attacks | For the purposes of a smooth demo, I've not touched XSS or CSRF as I can't be sure what ports or similar you'll end up using when you try to run it.  In terms of SQL injection, I am relying on the ORM for this, and only running 'select' queries in raw, which I'm not passing any variables into.  I've avoided concatenating, have bolted on some basic validation and used up to date technology to avoid vulnerabilities.  

If this were a production based environment I would likely rely on Laravels CORS settings to lock down cross site attacks to domains only I approve of.  I could also make use of the XSRF token to ensure that requests are secure and approved. |
| Include a Readme | Done and done! :) |

### Technology Notes

| Category | Technology |
| -------- | -----------|
| Front End | Vue 3.0 - Vite, Bootstrap |
| Back End | Laravel 10, PHPUnit |
| Database | MySQL 8 |
| Containerisation | Docker |
| Emails | Mailhog |
| Version Control | Git, Github |

## Logging in
You will be able to generate your own user using the front end, however, one has been provided for you.  If you would like to fast track to 'logged in', then you can use `test@test.com` with the password `secret`.  Please note that there may be a redirect issue between page 1 and 2 if you log in.  This was an intermittent issue that I didn't have time to resolve.  Once you have logged in, you can go directly to `http://localhost:5173/landing` to prevent the redirect.

## Considerations for Improvement
Had I more time I would implement the following:
 - Use of scopes instead of hard coded queries on the relationships, giving us the opportunity to return data or not.
 - Recursive templates on the front end, giving us an infinitely deep amount of replies to display.
 - Implement missing functionality (profile edit, add comment, filter comment by date, etc).
 - Integrate Redis as a cache platform to speed up queries.
 - Implement redirect if access token expires.
 - Inline error handling.
 - Feature tests for fail cases, not just success cases.  EG 'it doesnt let a user delete someone elses comment'.
 - Role based permissions and/or policies to handle ownership rather than form requests.
 - Finalise implementation of containerised 'production ready' vue platform rather than running the dev version.


## Screenshots!

### Login Page
![image](https://github.com/Vince-C9/HFS-CommunityPost/assets/78065068/cb92378f-be9a-406e-8168-38ac54710fd2)

### Registration Page
![image](https://github.com/Vince-C9/HFS-CommunityPost/assets/78065068/d17371b3-4007-4529-be36-992269707fbe)

### Forgot Password Page
![image](https://github.com/Vince-C9/HFS-CommunityPost/assets/78065068/8b539555-f3e8-4e10-9ccb-aeeba44315b0)

### Reset Password Email
![image](https://github.com/Vince-C9/HFS-CommunityPost/assets/78065068/513866ac-02c2-43b6-9630-d07190b4676f)

### Reset Password Email Content
![image](https://github.com/Vince-C9/HFS-CommunityPost/assets/78065068/5778e5cf-2bf4-4f59-baf4-e8b44c31b692)

### Reset Password Page
![image](https://github.com/Vince-C9/HFS-CommunityPost/assets/78065068/2a6ecb10-d171-4f3e-96ba-56fae42fd37d)

### Landing Page
![image](https://github.com/Vince-C9/HFS-CommunityPost/assets/78065068/934d3774-d78b-49a8-82cd-f16086e28e2d)

### Voting load state
![image](https://github.com/Vince-C9/HFS-CommunityPost/assets/78065068/ad20a827-0abc-4e52-a642-0641ebdee484)

### Comments
![image](https://github.com/Vince-C9/HFS-CommunityPost/assets/78065068/e5b38417-42e4-4f2a-b47b-0b84d9e2bb1e)

### Replies
![image](https://github.com/Vince-C9/HFS-CommunityPost/assets/78065068/c003c08d-84f1-4470-b6f1-dee7516a87e2)


