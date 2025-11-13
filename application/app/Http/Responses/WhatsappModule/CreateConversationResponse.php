<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [create conversation] process for the whatsapp module
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\WhatsappModule;
use Illuminate\Contracts\Support\Responsable;

class CreateConversationResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for whatsapp conversation
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        //render the form
        $html = view('pages/whatsapp/components/create/wrapper', compact('connections', 'clients', 'users', 'tags', 'url'))->render();
        $jsondata['dom_html'][] = array(
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html);

        //show modal footer (but it should be hidden as button is in form)
        $jsondata['dom_visibility'][] = array('selector' => '#commonModalFooter', 'action' => 'hide');

        // POSTRUN FUNCTIONS - initialize select2 dropdowns
        $jsondata['postrun_functions'][] = [
            'value' => 'NXWhatsappConversationForm',
        ];

        //ajax response
        return response()->json($jsondata);

    }

}
