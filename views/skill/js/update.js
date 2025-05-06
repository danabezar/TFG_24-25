$(document).ready(function() {
    $("#type-select").change(function() {
        let optionValues = [];
        let typeSelector = $('#type-select');
        let attributeSelector = $('#attribute-select');
        let currentType = typeSelector.val();

        switch(currentType){
            case "Stat Boost":
                optionValues = ["Health", "Strength", "Magic", "Skill", "Speed", "Luck", "Defense", "Resistance"];
                break;
            default: 
                optionValues = ["Attack", "Hit"];
                break;
        }

        attributeSelector.empty();

        for(let i = 0; i < optionValues.length; i++){
            let newOption = `<option value="${optionValues[i]}">${optionValues[i]}</option>`;
            attributeSelector.append(newOption);
        }
    });
});