<?php

namespace ComplyCube\Model;

use Carbon\Carbon;
use JsonSerializable;
use ReflectionObject;
use stdClass;

abstract class Model implements JsonSerializable
{
    public function __construct($data = null)
    {
        if (!isset($data)) {
            foreach (
                (new ReflectionObject($this))->getProperties()
                as $property
            ) {
                $property->setAccessible(true);
                if (!$property->isInitialized($this)) {
                    $property->setValue($this, null);
                }
            }
        } else {
            $this->load((object) $data);
        }
    }

    public function load(stdClass $response): void
    {
        foreach ((new ReflectionObject($this))->getProperties() as $property) {
            if (!property_exists($response, $property->name)) {
                $this->{$property->name} = null;
            } else {
                $property_type = !is_null($property->getType())
                    ? $property->getType()->getName()
                    : null;

                if ($property_type) {
                    if (
                        in_array($property_type, [
                            "bool",
                            "int",
                            "float",
                            "string",
                        ])
                    ) {
                        $this->{$property->name} = $response->{$property->name};
                    } elseif ($property_type == "Carbon\Carbon") {
                        switch (gettype($response->{$property->name})) {
                            case Carbon::class:
                                $this->{$property->name} =
                                    $response->{$property->name};
                                break;

                            case "string":
                                $this->{$property->name} = Carbon::parse(
                                    $response->{$property->name}
                                );
                                break;

                            default:
                                $this->{$property->name} = null;
                                break;
                        }
                    }
                }
            }
        }
    }

    public function jsonSerialize(): mixed
    {
        return array_filter(
            array_map(function ($value) {
                return $value instanceof Carbon
                    ? $value->toIso8601ZuluString()
                    : $value;
            }, get_object_vars($this)),
            function ($value) {
                return $value !== null;
            }
        );
    }
}
