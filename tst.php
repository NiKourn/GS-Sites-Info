<?php
/**
 * WordPress User Page
 *
 * Handles authentication, registering, resetting passwords, forgot password,
 * and other user handling.
 *
 * @package WordPress
 */

/** Make sure that the WordPress bootstrap has run before continuing. */
require __DIR__ . '/wp-load.php';

?>

<form id="searchForm">
    <label for="search">Search for a fruit:</label>
    <input type="text" id="search" name="search" placeholder="Enter a fruit" class="input-field">
</form>

<div class="custom-dropdown" id="dropdownMenu">
    <div class="dropdown-content" id="fruitDropdown">
        <!-- Options will be populated dynamically -->
    </div>
</div>

<script>
    let searchField = document.getElementById('search');
    let fruitDropdown = document.getElementById('fruitDropdown');
    let allFruits = [];

    // Fetch all fruits initially
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                allFruits = xhr.responseText.split('<br>');
                allFruits.pop(); // Remove the last empty element

                allFruits.forEach(function(fruit) {
                    let option = document.createElement('div');
                    option.classList.add('dropdown-item');
                    option.textContent = fruit;
                    option.addEventListener('click', function() {
                        searchField.value = fruit;
                        closeDropdown();
                    });
                    fruitDropdown.appendChild(option);
                });
            } else {
                console.error('AJAX request failed.');
            }
        }
    };

    xhr.open('GET', 'search.php', true); // Fetch all fruits initially
    xhr.send();

    searchField.addEventListener('input', function() {
        let searchTerm = this.value.trim().toLowerCase();
        filterFruits(searchTerm);
    });

    searchField.addEventListener('click', function() {
        let searchTerm = this.value.trim().toLowerCase();
        filterFruits(searchTerm);
    });

    function filterFruits(searchTerm) {
        fruitDropdown.innerHTML = '';

        let filteredFruits = allFruits.filter(function(fruit) {
            return fruit.toLowerCase().includes(searchTerm);
        });

        filteredFruits.forEach(function(fruit) {
            let option = document.createElement('div');
            option.classList.add('dropdown-item');
            option.textContent = fruit;
            option.addEventListener('click', function() {
                searchField.value = fruit;
                selectFruit(fruit);
            });
            fruitDropdown.appendChild(option);
        });

        openDropdown();
    }

    function openDropdown() {
        fruitDropdown.style.display = 'block';
        let rect = searchField.getBoundingClientRect();
        fruitDropdown.style.top = (rect.bottom + window.scrollY) + 'px'; // Adjusted to include scrolling
        fruitDropdown.style.left = rect.left + 'px';
    }

    function closeDropdown() {
        fruitDropdown.style.display = 'none';
    }

    function selectFruit( fruit ) {
        searchField.value = fruit;
        console.log(fruit);
        closeDropdown();
    }
</script>

<style>
    /* Styles for the custom dropdown */
    .input-field {
        display: block;
        width: 200px; /* Adjust the width as needed */
        padding: 8px;
        margin-bottom: 10px;
    }

    .custom-dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #fff;
        border: 1px solid #ccc;
        z-index: 1;
        max-height: 150px; /* Adjust as needed */
        overflow-y: auto;
    }

    .dropdown-item {
        padding: 8px 16px;
        cursor: pointer;
    }

    .dropdown-item:hover {
        background-color: #f9f9f9;
    }

</style>