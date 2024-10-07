document.addEventListener('DOMContentLoaded', function() {
	showRegionsList();
});

function showRegionsList() {
	// Assuming 'ubiperu.ubigeos.d' is the list of departments
	ubiperu.ubigeos.d.forEach(function(department) {

		var option = document.createElement('option');

		// Use data-id to store department ID, value for the display name
		option.setAttribute('data-id', department.dId);
		option.name = 'departamento';
		option.value = department.n;  // Set value to the department name
		option.addEventListener('click', onChange_Region, false);

		// Using 'n' for the name (from the optimized JSON)
		option.textContent = department.n;

		document.querySelector('#cb_departamento').appendChild(option);
	});
}

function onChange_Region() {
	// Clear province and district dropdowns, but keep the first option
	clearSelectOptions('#cb_provincia', 'Seleccione provincia');
	clearSelectOptions('#cb_distrito', 'Seleccione distrito');

	// Get the selected department's ID from data-id
	var departmentId = document.querySelector('#cb_departamento option:checked').getAttribute('data-id');

	showProvincesList(departmentId);
}

function showProvincesList(departmentId) {
	// Find the selected department
	const selectedDepartment = ubiperu.ubigeos.d.find(dept => dept.dId === departmentId);

	// Iterate over provinces in the selected department
	selectedDepartment.p.forEach(function(province) {

		var option = document.createElement('option');

		// Use data-id to store province ID, value for the display name
		option.setAttribute('data-id', province.pId);
		option.name = 'provincia';
		option.value = province.n;  // Set value to the province name
		option.addEventListener('click', onChange_Province, false);

		// Using 'n' for the name (from the optimized JSON)
		option.textContent = province.n;

		document.querySelector('#cb_provincia').appendChild(option);
	});
}

function onChange_Province() {
	// Clear districts dropdown but keep the first option
	clearSelectOptions('#cb_distrito', 'Seleccione distrito');

	// Get the selected province's ID from data-id
	var provinceId = document.querySelector('#cb_provincia option:checked').getAttribute('data-id');
	var departmentId = document.querySelector('#cb_departamento option:checked').getAttribute('data-id');
	
	showDistrictsList(departmentId, provinceId);
}

function showDistrictsList(departmentId, provinceId) {
	// Find the selected department
	const selectedDepartment = ubiperu.ubigeos.d.find(dept => dept.dId === departmentId);

	// Find the selected province within the department
	const selectedProvince = selectedDepartment.p.find(prov => prov.pId === provinceId);

	// Iterate over districts in the selected province
	selectedProvince.t.forEach(function(district) {

		var option = document.createElement('option');

		// Use data-id to store district ID, value for the display name
		option.setAttribute('data-id', district.tId);
		option.name = 'distrito';
		option.value = district.n;  // Set value to the district name

		// Using 'n' for the name (from the optimized JSON)
		option.textContent = district.n;

		document.querySelector('#cb_distrito').appendChild(option);
	});
}

function clearSelectOptions(selectId, defaultText) {
	// Clear options, but keep the first disabled option with the default text
	const select = document.querySelector(selectId);
	select.innerHTML = '';  // Clear all options
	const defaultOption = document.createElement('option');
	defaultOption.disabled = true;
	defaultOption.selected = true;
	defaultOption.textContent = defaultText;
	select.appendChild(defaultOption);
}
