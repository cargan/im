var API = '/api.php';

var matchMachines = function(items) {
    var mach = {};
    items.forEach(function(val, key) {
        if (typeof mach[val.node] === "undefined") {
            mach[val.node] =[];
        }
        mach[val.node].push({
            name: val.name,
            value: val.mcount
        });
    });
    return mach;
};

var machinesToNodes = function(nodes, mach) {
    var machines = [];
    nodes.forEach(function(val, key) {
        var M = [];
        if (typeof mach[val.node] !== "undefined") {
            M = mach[val.node];
        }
        machines.push({
            node: val.node,
            machines: M
        });
    });

    return machines;
};

var renderMachinesTable = function(machines) {
    var template = $('#Mtemplate').html();
    Mustache.parse(template);
    var rendered = Mustache.render(template, {
        machines: machines
    });

    return rendered;
}

var addMachine = function(data) {
    var postData = {
        a: 'addMachine',
        location: data.location,
        count: data.count,
        machine: data.machine
    };

    $.post(API, postData, function(result) {
        console.log(result);
    });
};

var drawMap = function(graph) {
    var data = {
        a: 'getNodesAndEdges'
    };

    $.get(API, data, function(response) {
        var nodes = {};
        response.result.nodes.forEach(function(val, key) {
            nodes[val.node] = graph.newNode({label: val.node});
        });

        response.result.edges.forEach(function(val, key) {
            graph.newEdge(
                nodes[val.nfrom],
                nodes[val.nto],
                {
                    color: Colors[key],
                    label: val.weight
                }
            );
        });

      var springy = window.springy = jQuery('#machines').springy({
        graph: graph,
        nodeSelected: function(node){
          console.log('Node selected: ' + JSON.stringify(node.data));
        }
      });

        machinesSummary(response.result.nodes);
        enemiesSummary(response.result.nodes);

    }, 'json');
};

var machinesSummary = function (nodes) {
    var data = {
        a: 'getMachines'
    };

    $.post(API, data, function(response) {
        var mach = matchMachines(response.result);
        var machines = machinesToNodes(nodes, mach);
        var rendered = renderMachinesTable(machines);

        $('td#terrainObjectsMachines').html(rendered);
    }, 'json');
}


var enemiesSummary = function (nodes) {
    var data = {
        a: 'getEnemies'
    };

    $.post(API, data, function(response) {
        var mach = matchMachines(response.result);
        var machines = machinesToNodes(nodes, mach);
        var rendered = renderMachinesTable(machines);
        $('td#terrainObjectsEnemies').html(rendered);
    }, 'json');
}




jQuery(function(){
    $('form#manipulateMachines').submit(function() {
        var data = {};
        data.location = $(this).find('#Location').val();
        data.count = $(this).find('#count').val();
        data.machine = $(this).find('#addMachine').val();
        addMachine(data);
        return false;
    });

    var graph = new Springy.Graph();
    drawMap(graph);
});


