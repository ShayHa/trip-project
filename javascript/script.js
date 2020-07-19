function changeWeight( ) {
    if( document.getElementById('weights').style.display === "block" ) {
        document.getElementById('weights').style.display = "none";
    } else {
        document.getElementById('weights').style.display = "block";
    }
}

function checkWeights() {
    var type_weight = parseInt( document.getElementById('type_weight').value );
    var theme_weight = parseInt( document.getElementById('theme_weight').value );
    var season_weight = parseInt( document.getElementById('season_weight').value );
    var price_weight = parseInt( document.getElementById('price_weight').value );
    var age_weight = parseInt( document.getElementById('age_weight').value );
    var total_weight = type_weight + theme_weight + season_weight + price_weight + age_weight;
    if( total_weight !== 100 ) {
        alert( "Total weights must be equal to 100%" );
        return false;
    }
    return true;
}

function updateTotalWeight( input ) {
    var type_weight = parseInt( document.getElementById('type_weight').value );
    var theme_weight = parseInt( document.getElementById('theme_weight').value );
    var season_weight = parseInt( document.getElementById('season_weight').value );
    var price_weight = parseInt( document.getElementById('price_weight').value );
    var age_weight = parseInt( document.getElementById('age_weight').value );
    var total_weight = type_weight + theme_weight + season_weight + price_weight + age_weight;
    document.getElementById('total_weight').innerHTML = parseInt(total_weight)+"%";
    // make the weight red if it passes 100
    if (total_weight >100){
        document.getElementById('total_weight').style.color = 'red';
    }
    else {
        document.getElementById('total_weight').style.color = 'white';
    }

}

function showComment() {

    if( document.getElementById('wrapper').style.display === "none" &
            document.getElementById('search_results') != null) {
        document.getElementById('wrapper').style.display = "block";
    } else {
        // alert('else');
    }
}

