<?php

namespace Gianfriaur\LaravelDynamicRelation\Describer\Validation;

enum ValidationResultEnum:string
{
    case ERROR = "error";
    case INFO = "info";
    case WARNING = "warning";
}
