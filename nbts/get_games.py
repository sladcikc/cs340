from __future__ import print_function
import mlbgame
import datetime

now = datetime.datetime.now()
year = now.year
month = now.month
day = now.day +1

games = mlbgame.day(year, month, day, home=None, away=None)

for game in games: 
    print(game.game_id)
