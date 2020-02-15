function displayMenu()
{
    var overlay = document.getElementById('menu-overlay');
		
	if (overlay.classList.contains('display'))
	{
		overlay.classList.remove('display');
	}
	else
	{
		overlay.classList.add('display');
	}
}