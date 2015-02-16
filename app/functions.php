<?php

function controller($shortName)
{
    list($shortClass, $shortMethod) = explode(':', $shortName, 2);

    return sprintf('Buster\Controller\%sController::%sAction', ucfirst($shortClass), $shortMethod);
}