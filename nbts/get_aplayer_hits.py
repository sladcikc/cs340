from __future__ import print_function
import mlbgame
import datetime
import json


now = datetime.datetime.now()
year = now.year
month = now.month
day = now.day -1


games = mlbgame.day(year, month, day, home=None, away=None)
stringy = "[\n"
for game in games: 
    stats=mlbgame.player_stats(game.game_id)
    for player in stats.away_batting:
        if(str(player.pos) != "P"):
            stringy = stringy + "\t\t{\n\t\t\t\"player_id\": " + "\"" + str(player.id) + "\",\n\t\t\t" + "\"player_name\": " + "\"" + player.name_display_first_last + "\",\n\t\t\t" + "\"hits\": " + "\"" + str(player.h) + "\",\n\t\t\t" + "\"at_bats\": " + "\"" + str(player.ab) + "\",\n\t\t\t" + "\"t_hits\": " + "\"" + str(player.s_h) + "\",\n\t\t\t" + "\"average\": \"" + str(player.avg) + "\"\n\t\t},\n"

stringy = stringy[:-2]
stringy += "\n\t]"
filename = "ahits_" + str(year) + "-" + str(month) + "-" + str(day) + ".json"
fh = open(filename, "w")
fh.write(stringy)