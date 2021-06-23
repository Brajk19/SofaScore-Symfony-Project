# SofaScore-Symfony-Project

### Instructions for setting up the application:

  1. Clone the repository
  2. Run Docker desktop app
  3. Open Windows Powershell (or other similar tool)
  4. Position yourself in repository folder and run `docker compose up -d`
  5. Run `docker compose exec php-fpm bash` and then `cd var/www/SofaScore-Project`
  6. Run `apt-get update` and `apt-get install php7.4-gd` 
  7. Run `php bin/console doctrine:schema:update --dump-sql` and copy all SQL queries to your PostgreSQL manager/IDE (e.g. DataGrip)
  8. Run `php bin/console init:database` and you are all set.
  
  
#### Next commands are not necessary but are very useful:
  
  1. `php bin/console add:dummyData` - creates all the matches and standings for randomly selected(or generated) category, competition, season and sport
  2. `php bin/console play:match` - plays next unfinished match in the chosen season and updates the standings. Keep Enter key pressed down to avoid having to write yes to play next match, it will speed up things
  3. `php bin/console create:users` - creates users whose API tokens are needed for JSON API (below). Save first token for future usage
  4. `php bin/console update:standings` - recalculates stats for two teams from match that's been manually adjusted/changed, you will be notified if this command should be ran
  
  
Visit http://localhost:8888/ for graphical user interface.


  
  
## JSON API routes:

  -I suggest installing [Modify Header Value (HTTP Headers)](https://chrome.google.com/webstore/detail/modify-header-value-http/cbdibdfhahmknbkkojljfncpnhmacdek) (or some other browser addon) so you can set the header for every request towards localhost:8888. Otherwise you'll need to write it every time you use curl command.
  
  -Example: [Screenshot from addon options](https://i.imgur.com/yHTVmQg.png)
  
  -Click on links if you've set headers, or use curl command with your token.



  1. Route for getting 5 most recent matches of every competitor: http://localhost:8888/recentMatches
    Token also need to be sent to gain access.
    
    curl --header "X-AUTH-TOKEN: 60d2679b81b4a" http://localhost:8888/recentMatches/
    
  2. Route for information about standings: http://localhost:8888/standingsInfo/1 - replace number with id of standings you want more info about
  
    curl --header "X-AUTH-TOKEN: 60d2679b81b4a" --request GET http://localhost:8888/standingsInfo/1
    
  3. Get all StandingsRow entites for given Standings (by id): http://localhost:8888/standings/1
  
    curl --header "X-AUTH-TOKEN: 60d2679b81b4a" --request GET http://localhost:8888/standings/1

#### Following routes use POST methed and require sending data

  4. Changing match info:
  
  -you can choose status code, start time, end time, and scores for each match period
  
  -"http://localhost:8888/setMatch/{id}"
      
      curl --header "Content-Type: application/json" --header "X-AUTH-TOKEN: 60d2679b81b4a" --request POST --data "{\"statusCode\":9,\"startTime\":\"22.06.2020 15:34\",\"homeScore\":[2,1],\"awayScore\":[0,1]}" http://localhost:8888/setMatch/3
    
  
  
  5. Changing Competition name:
  
    curl --header "Content-Type: application/json" --header "X-AUTH-TOKEN: 60d2679b81b4a" --request POST --data "{\"name\":\"UEFA Champions League\"}" http://localhost:8888/changeCompetition/3
    
    
  6. Changing Competitor's country and/or name:
  
    curl --header "Content-Type: application/json" --header "X-AUTH-TOKEN: 60d2679b81b4a" --request POST --data "{\"name\":\"Dragons\",\"isoAlpha2\":\"HR\"}" http://localhost:8888/changeCompetitor/3
    
    
  7. Changing Season's name and/or start time and/or end time:
  
    curl --header "Content-Type: application/json" --header "X-AUTH-TOKEN: 60d2679b81b4a" --request POST --data "{\"name\":\"Season 2077\",\"seasonStart\":\"01.01.2077 06:00\",\"seasonEnd\":\"30.12.2077 12:34\"}" http://localhost:8888/changeSeason/1
