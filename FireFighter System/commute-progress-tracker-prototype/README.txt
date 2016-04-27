A Pen created at CodePen.io. You can find this one at http://codepen.io/johan/pen/FILzw.

 This is a work in progress. Feel free to add ?from=start-address&to=end-address to the url to use this proto-tool for travels of your own. :-)

Next few steps:

* render time arcs of all discrete transit steps, angle by duration
* render space arcs of all discrete transit steps, angle by duration, not distance
* tint the completed time arc segment (0% to current %) a little darker
* tint the completed space arc segment (0% to current %) a little darker
* show upcoming transit type in the middle of the arc circles
* improve the curve-fitting algorithm around sharper edges of the trip so we don't skip past such stations where gps data is rough?
* improve gps location hack to show (lack of) accuracy, iOS style

Done:
* employ gps location beside mouse cursor to decide where we are right now
