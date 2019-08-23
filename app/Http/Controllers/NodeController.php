<?php

namespace App\Http\Controllers;

use App\Models\Node;
use Illuminate\Http\Request;

class NodeController extends Controller
{

    public function setNode(Request $request)
    {
        $input = $this->validate($request, [
            "uid" => "required|string|max:21",
            "config" => "required|json",
            "files" => "required|array",
            "files.*" => "file|mimes:png",
        ]);

        $node = new Node();
        $node->uid = $input["uid"];
        $node->active = true;
        $node->save();

        $this->parserConnector->setConfig($node, $input["config"]);
        foreach ($input["files"] as $file) {
            $this->parserConnector->addFile($node, $file);
        }

        return $this->responseFactory->json([
            "success" => true,
            "data" => $node->toArray(),
        ]);
    }

}
