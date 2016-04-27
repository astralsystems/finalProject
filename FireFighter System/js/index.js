(function() {
  var $, GM, GMEvent, LL, LatLng, addDepartures, blue_dot, center_at, clutter, cur_loc, directions, drawArcs, geo, height, hypothenuse, init, kdtree, ll, map, map_div, meters, mousemove, ok, pie, plot, pos, px_p_m, query, radius, renderer, route, spaceArc, timeArc, transitLayer, updateInterpolator, updatePath, width;

  window.debug = !!location.hash.length;

  Array.prototype.flatten = window.Array.prototype.flatten = function() {
    var flatten;
    return this.reduce((flatten = function(a, b) {
      if (!(a instanceof Array)) {
        a = [a];
      }
      if (b instanceof Array) {
        b = b.flatten();
      }
      return a.concat(b);
    }), []);
  };

  width = 200;

  height = 200;

  radius = Math.min(width, height) >> 1;

  timeArc = d3.svg.arc().outerRadius(radius - 10).innerRadius(radius - 40);

  spaceArc = d3.svg.arc().outerRadius(radius - 40).innerRadius(radius - 70);

  pie = d3.layout.pie().sort(null).value(function(d) {
    return d.duration;
  });

  drawArcs = function() {
    var svg;
    svg = d3.select('#progress').append('svg').attr('width', width).attr('height', height).append('g').attr('transform', "translate(" + (width >> 1) + "," + (height >> 1) + ")");
    return [
      {
        title: 'time',
        progress: 0.75,
        arc: timeArc
      }, {
        title: 'space',
        progress: 0.80,
        arc: spaceArc
      }
    ].forEach(function(d, i, all) {
      var arc, g;
      arc = d.arc;
      g = svg.selectAll(".arc." + d.title).data(pie([
        {
          duration: d.progress
        }, {
          duration: 1 - d.progress
        }
      ])).enter().append('g').classed("arc " + d.title, true);
      g.append('path').attr('d', arc).style('fill', function(d, i) {
        if (i) {
          return 'transparent';
        }
      });
      return g.append('text').attr('transform', function(d) {
        return "translate(" + (arc.centroid(d)) + ")";
      }).attr('dy', '.35em').style('text-anchor', 'middle').text(function() {
        return d.title;
      });
    });
  };

  query = (function() {
    var data;
    data = {};
    location.search.replace(/\+/g, '%20').split('&').forEach(function(kv) {
      var k, v, x;
      kv = /^\??([^=]*)=(.*)/.exec(kv);
      if (!kv) {
        return;
      }
      x = kv[0], k = kv[1], v = kv[2];
      data[(function() {
        try {
          return decodeURIComponent(k);
        } catch (_error) {}
      })()] = (function() {
        try {
          return decodeURIComponent(v);
        } catch (_error) {}
      })();
      return null;
    });
    return data;
  })();

  $ = function(id) {
    return document.getElementById(id);
  };

  ['from', 'to'].forEach(function(x) {
    if (query[x]) {
      return $(x).value = query[x];
    }
  });

  GM = window.GM = google.maps;

  GMEvent = window.GMEvent = GM.event;

  LatLng = window.LatLng = GM.LatLng;

  meters = GM.geometry.spherical.computeDistanceBetween;

  clutter = window.clutter = [];

  map_div = $('map');

  center_at = new LatLng(51.538551, -0.016633);

  directions = window.directions = new GM.DirectionsService;

  renderer = window.renderer = new GM.DirectionsRenderer;

  hypothenuse = function(x, y) {
    return Math.sqrt(x * x + y * y);
  };

  blue_dot = new GM.MarkerImage('http://johan.github.com/GeoLocateMe/bluedot.png', null, null, new GM.Point(9, 9), new GM.Size(17, 17));

  transitLayer = map = px_p_m = kdtree = cur_loc = geo = null;

  window.LL = LL = function(x) {
    return new LatLng(x[0], x[1]);
  };

  window.ll = ll = function(x) {
    return [x.lat(), x.lng()];
  };

  plot = window.plot = function(at, icon) {
    if (at.length) {
      at = LL(at);
    }
    if (typeof icon !== 'object') {
      icon = void 0;
    }
    icon = new GM.Marker({
      map: map,
      position: at,
      icon: icon
    });
    clutter.push(icon);
    return icon;
  };

  ok = function(e) {
    var acc, at, lat, lng;
    at = e.coords;
    lat = at.latitude;
    lng = at.longitude;
    acc = at.accuracy;
    console.log("at " + (lat.toFixed(5)) + "," + (lng.toFixed(5)) + " ± " + acc + "m");
    return pos(lat, lng, acc);
  };

  pos = function(lat, lng, acc) {
    var here;
    if (typeof lat === 'object') {
      here = lat;
    } else {
      here = new LatLng(lat, lng);
    }
    if (cur_loc) {
      return cur_loc.setPosition(here);
    } else {
      return cur_loc = window.cur_loc = new GM.Marker({
        position: here,
        title: 'Current Location',
        icon: blue_dot,
        map: map,
        flat: true,
        optimized: false
      });
    }
  };

  mousemove = function(e) {
    var at, hovered, nearest;
    window.e = e;
    at = window.at = e.latLng;
    window.hovered = hovered = ll(at);
    if (!kdtree) {
      return;
    }
    window.nearest = nearest = kdtree.getNearestNeighbour(hovered);
    if (cur_loc) {
      cur_loc.setPosition(LL(nearest));
    }
    return void 0;
  };

  GMEvent.addDomListener(window, 'load', init = function() {
    var arcs, autocomplete, control, input, mapOptions;
    mapOptions = {
      zoom: 14,
      mapTypeId: GM.MapTypeId.ROADMAP
    };
    map = window.map = new GM.Map($('map'), mapOptions);
    GMEvent.addListener(map, 'zoom_changed', function() {
      return GMEvent.addListenerOnce(map, 'bounds_changed', updatePath);
    });
    GMEvent.addDomListener(map, 'mousemove', mousemove);
    GMEvent.addDomListener($('go'), 'click', route);
    geo = navigator.geolocation.watchPosition(ok);
    input = $('from');
    autocomplete = new GM.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);
    transitLayer = new GM.TransitLayer;
    map.controls[GM.ControlPosition.RIGHT_BOTTOM].push(arcs = $('progress'));
    map.controls[GM.ControlPosition.TOP_RIGHT].push(control = $('transit-wpr'));
    GMEvent.addDomListener(control, 'click', function() {
      return transitLayer.setMap((transitLayer.getMap() ? null : map));
    });
    addDepartures();
    route();
    return drawArcs();
  });

  addDepartures = function() {
    var at, h, hh, m, mm, results;
    at = $('depart');
    h = 0;
    results = [];
    while (h < 24) {
      m = 0;
      while (m < 60) {
        hh = h < 10 ? "0" + h : h;
        mm = m < 10 ? "0" + m : m;
        at.innerHTML += "<option>" + hh + ":" + mm + "</option>";
        m += 15;
      }
      results.push(h++);
    }
    return results;
  };

  route = function() {
    var departure, departureTime, hhmm, ms, now, request, time, tzOffset;
    departure = $('depart').value;
    now = new Date;
    tzOffset = (now.getTimezoneOffset() + 60) * 60e3;
    hhmm = departure.split(':');
    time = new Date;
    time.setHours(hhmm[0]);
    time.setMinutes(hhmm[1]);
    ms = time.getTime() - tzOffset;
    if (ms < now.getTime()) {
      ms += 24 * 60 * 60e3;
    }
    departureTime = new Date(ms);
    request = {
      origin: $('from').value,
      destination: $('to').value,
      travelMode: GM.DirectionsTravelMode.DRIVING,
      provideRouteAlternatives: true,
      transitOptions: {
        departureTime: departureTime
      }
    };
    $('panel').innerHTML = '';
    return directions.route(request, function(response, status) {
      window.response = response;
      window.mystatus = status;
      return updatePath('new', (status === GM.DirectionsStatus.OK ? response : null));
    });
  };

  updatePath = function(action, data) {
    var leg;
    console.log('updatePath');
    if (action === 'new') {
      if (data === null) {
        renderer.setMap(null);
        return renderer.setPanel(null);
      } else {
        renderer.setMap(map);
        renderer.setPanel($('panel'));
        renderer.setDirections(data);
        leg = window.leg = response.routes[0].legs[0];
        pos(leg.start_location);
        return updateInterpolator(leg);
      }
    } else {
      if (leg) {
        return updateInterpolator(window.leg = leg);
      }
    }
  };

  updateInterpolator = function() {
    var before, bounds, coords, min, orig, path, px, uniq;
    console.log('updateInterpolator');
    bounds = map.getBounds();
    if (!bounds) {
      return setTimeout(updateInterpolator, 100);
    }
    px_p_m = hypothenuse(map_div.offsetWidth, map_div.offsetHeight) / meters(bounds.getSouthWest(), bounds.getNorthEast());
    px = 2;
    min = px / px_p_m;
    uniq = {};
    orig = [];
    leg.steps.map(function(x) {
      return x.lat_lngs;
    }).flatten().forEach(function(x) {
      var name, ref;
      if ((ref = (uniq[name = x + ''] != null ? uniq[name] : uniq[name] = true)) != null ? ref : false) {
        return orig.push(x);
      }
    });
    path = orig.reduce(function(a, p2) {
      var dist, dx, dy, mid, n, p1, p1_lat, p1_lng, segments;
      if (!(a instanceof Array)) {
        a = [a];
      }
      p1 = a[a.length - 1];
      dist = meters(p1, p2);
      if (dist <= min) {
        mid = [];
      } else {
        segments = Math.floor(dist / min);
        dx = (p2.lng() - (p1_lng = p1.lng())) / segments;
        dy = (p2.lat() - (p1_lat = p1.lat())) / segments;
        mid = (function() {
          var j, ref, results;
          results = [];
          for (n = j = 1, ref = segments; 1 <= ref ? j <= ref : j >= ref; n = 1 <= ref ? ++j : --j) {
            results.push(new LatLng(p1_lat + dy * n, p1_lng + dx * n));
          }
          return results;
        })();
      }
      return a.concat(mid, p2);
    });
    clutter = window.clutter = clutter.filter(function(x) {
      if (x === cur_loc) {
        return true;
      } else {
        return x.setMap(null);
      }
    });
    window.before = before = orig.map(ll);
    window.coords = coords = path.map(ll);
    window.kdtree = kdtree = new KDTree(coords);
    if (window.debug) {
      return before.map(plot);
    }
  };

}).call(this);

var mapOptions = {
    zoom: 16,
    center: myLatlng,
    scrollwheel: false

}
