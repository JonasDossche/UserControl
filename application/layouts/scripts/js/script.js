    var users;
    
    window.onload = function(){ 
        if(document.editgroup.hidden.value != "") {   	
			users = document.editgroup.hidden.value.split(",");
        } else {
			users = new Array();
        } 
    	    	
  	  	var selectmenu=document.getElementById("users");
	    selectmenu.onchange=function() {
	    	var chosenoption=this.options[this.selectedIndex];
	    	
	    	if(chosenoption.value != 0) 
		    {	    			    		
	    		this.remove(this.selectedIndex);
	    		users.push(chosenoption.value);
	    		document.editgroup.hidden.value = users; 

	    		var tbl = document.getElementById('tblUsers');
	    		var lastRow = tbl.rows.length;	    		  
	    		var row = tbl.insertRow(lastRow); 
				row.id = 'row' + chosenoption.value;
					
	    		var cellLeft = row.insertCell(0);
	    		cellLeft.innerHTML = chosenoption.innerHTML;

	    		var cellRight = row.insertCell(1);	    		
	    		var link = document.createElement('a');
	    		link.setAttribute('onclick', 'removeUser(' + chosenoption.value + ',\'' + chosenoption.innerHTML + '\')');
	    		link.setAttribute('href', '#');	
	    		link.innerHTML = "Delete";
	    		cellRight.appendChild(link);
	    			 
	    	}
	    	    	
	    }	   
	    
    }

    function removeUser(id,mail) {        
        removeItem(users,id);
        document.editgroup.hidden.value = users;       
    	var row = document.getElementById('row'+id);
    	row.parentElement.removeChild(row); 
    	var select = document.getElementById("users");
    	select.options[select.options.length] = new Option(mail, id);    	
    	return false;
    }

    function removeItem(originalArray, itemToRemove) {
    	var j = 0;
    	while (j < originalArray.length) {
    	
	    	if (originalArray[j] == itemToRemove) {
	    	originalArray.splice(j, 1);
	    	} else {
	       		j++;
	       	}
    	}
    } 