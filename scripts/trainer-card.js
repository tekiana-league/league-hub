function flipCard(card)
{
    if (card.children[0].classList.contains('flip'))
	{
		card.children[0].classList.remove('flip');
	}
	else
	{
		card.children[0].classList.add('flip');
	}
}