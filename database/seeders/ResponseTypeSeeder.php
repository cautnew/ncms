<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Core\ResponseTypes\ResponseType;
use App\Enums\ResponseTypes;

class ResponseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            // Informational 1xx
            ['id' => ResponseTypes::CONTINUE->value, 'name' => 'Continue', 'code' => 'CONTINUE', 'description' => 'This interim response indicates that the client should continue the request or ignore the response if the request is already finished.'],
            ['id' => ResponseTypes::SWITCHING_PROTOCOLS->value, 'name' => 'Switching Protocols', 'code' => 'SWITCHING_PROTOCOLS', 'description' => 'This code is sent in response to an Upgrade request header from the client and indicates the protocol the server is switching to.'],
            ['id' => ResponseTypes::PROCESSING->value, 'name' => 'Processing', 'code' => 'PROCESSING', 'description' => 'This code indicates that the server has received and is processing the request, but no response is available yet.'],
            ['id' => ResponseTypes::EARLY_HINTS->value, 'name' => 'Early Hints', 'code' => 'EARLY_HINTS', 'description' => 'This status code is primarily intended to be used with the Link header, letting the user agent start preloading resources while the server prepares a response.'],
            
            // Successful 2xx
            ['id' => ResponseTypes::OK->value, 'name' => 'OK', 'code' => 'OK', 'description' => 'The request succeeded. The result meaning of "success" depends on the HTTP method.'],
            ['id' => ResponseTypes::CREATED->value, 'name' => 'Created', 'code' => 'CREATED', 'description' => 'The request succeeded, and a new resource was created as a result. This is typically the response sent after POST requests, or some PUT requests.'],
            ['id' => ResponseTypes::ACCEPTED->value, 'name' => 'Accepted', 'code' => 'ACCEPTED', 'description' => 'The request has been received but not yet acted upon. It is noncommittal, since there is no way in HTTP to later send an asynchronous response.'],
            ['id' => ResponseTypes::NON_AUTHORITATIVE_INFORMATION->value, 'name' => 'Non-Authoritative Information', 'code' => 'NON_AUTHORITATIVE_INFORMATION', 'description' => 'This response code means the returned meta-information is not exactly the same as is available from the origin server, but is collected from a local or a third-party copy.'],
            ['id' => ResponseTypes::NO_CONTENT->value, 'name' => 'No Content', 'code' => 'NO_CONTENT', 'description' => 'There is no content to send for this request, but the headers may be useful.'],
            ['id' => ResponseTypes::RESET_CONTENT->value, 'name' => 'Reset Content', 'code' => 'RESET_CONTENT', 'description' => 'Tells the user agent to reset the document which sent this request.'],
            ['id' => ResponseTypes::PARTIAL_CONTENT->value, 'name' => 'Partial Content', 'code' => 'PARTIAL_CONTENT', 'description' => 'This response code is used when the Range header is sent from the client to request only part of a resource.'],
            ['id' => ResponseTypes::MULTI_STATUS->value, 'name' => 'Multi-Status', 'code' => 'MULTI_STATUS', 'description' => 'Conveys information about multiple resources, for situations where multiple status codes might be appropriate.'],
            ['id' => ResponseTypes::ALREADY_REPORTED->value, 'name' => 'Already Reported', 'code' => 'ALREADY_REPORTED', 'description' => 'Used inside a <dav:propstat> response element to avoid repeatedly enumerating the internal members of multiple bindings to the same collection.'],
            ['id' => ResponseTypes::IM_USED->value, 'name' => 'IM Used', 'code' => 'IM_USED', 'description' => 'The server has fulfilled a GET request for the resource, and the response is a representation of one or more instance-manipulations applied to the current instance.'],
            
            // Redirection 3xx
            ['id' => ResponseTypes::MULTIPLE_CHOICES->value, 'name' => 'Multiple Choices', 'code' => 'MULTIPLE_CHOICES', 'description' => 'The request has more than one possible response. The user agent or user should choose one of them.'],
            ['id' => ResponseTypes::MOVED_PERMANENTLY->value, 'name' => 'Moved Permanently', 'code' => 'MOVED_PERMANENTLY', 'description' => 'The URL of the requested resource has been changed permanently. The new URL is given in the response.'],
            ['id' => ResponseTypes::FOUND->value, 'name' => 'Found', 'code' => 'FOUND', 'description' => 'This response code means that the URI of requested resource has been changed temporarily. Therefore, this same URI should be used by the client in future requests.'],
            ['id' => ResponseTypes::SEE_OTHER->value, 'name' => 'See Other', 'code' => 'SEE_OTHER', 'description' => 'The server sent this response to direct the client to get the requested resource at another URI with a GET request.'],
            ['id' => ResponseTypes::NOT_MODIFIED->value, 'name' => 'Not Modified', 'code' => 'NOT_MODIFIED', 'description' => 'This is used for caching purposes. It tells the client that the response has not been modified, so the client can continue to use the same cached version of the response.'],
            ['id' => ResponseTypes::USE_PROXY->value, 'name' => 'Use Proxy', 'code' => 'USE_PROXY', 'description' => 'Defined in a previous version of the HTTP specification to indicate that a requested response must be accessed by a proxy. It has been deprecated due to security concerns.'],
            ['id' => ResponseTypes::UNUSED->value, 'name' => 'Unused', 'code' => 'UNUSED', 'description' => 'This response code is no longer used; it is just reserved. It was used in a previous version of the HTTP/1.1 specification.'],
            ['id' => ResponseTypes::TEMPORARY_REDIRECT->value, 'name' => 'Temporary Redirect', 'code' => 'TEMPORARY_REDIRECT', 'description' => 'The server sends this response to direct the client to get the requested resource at another URI with the same method that was used in the prior request.'],
            ['id' => ResponseTypes::PERMANENT_REDIRECT->value, 'name' => 'Permanent Redirect', 'code' => 'PERMANENT_REDIRECT', 'description' => 'This means that the resource is now permanently located at another URI. The user agent must not change the HTTP method used.'],
            
            // Client Error 4xx
            ['id' => ResponseTypes::BAD_REQUEST->value, 'name' => 'Bad Request', 'code' => 'BAD_REQUEST', 'description' => 'The server cannot or will not process the request due to something that is perceived to be a client error.'],
            ['id' => ResponseTypes::UNAUTHORIZED->value, 'name' => 'Unauthorized', 'code' => 'UNAUTHORIZED', 'description' => 'Although the HTTP standard specifies "unauthorized", semantically this response means "unauthenticated". The client must authenticate itself to get the requested response.'],
            ['id' => ResponseTypes::PAYMENT_REQUIRED->value, 'name' => 'Payment Required', 'code' => 'PAYMENT_REQUIRED', 'description' => 'This response code is reserved for future use. The initial aim for creating this code was using it for digital payment systems.'],
            ['id' => ResponseTypes::FORBIDDEN->value, 'name' => 'Forbidden', 'code' => 'FORBIDDEN', 'description' => 'The client does not have access rights to the content; that is, it is unauthorized, so the server is refusing to give the requested resource. The client\'s identity is known to the server.'],
            ['id' => ResponseTypes::NOT_FOUND->value, 'name' => 'Not Found', 'code' => 'NOT_FOUND', 'description' => 'The server cannot find the requested resource. In the browser, this means the URL is not recognized.'],
            ['id' => ResponseTypes::METHOD_NOT_ALLOWED->value, 'name' => 'Method Not Allowed', 'code' => 'METHOD_NOT_ALLOWED', 'description' => 'The request method is known by the server but is not supported by the target resource.'],
            ['id' => ResponseTypes::NOT_ACCEPTABLE->value, 'name' => 'Not Acceptable', 'code' => 'NOT_ACCEPTABLE', 'description' => 'This response is sent when the web server, after performing server-driven content negotiation, doesn\'t find any content that conforms to the criteria given by the user agent.'],
            ['id' => ResponseTypes::PROXY_AUTHENTICATION_REQUIRED->value, 'name' => 'Proxy Authentication Required', 'code' => 'PROXY_AUTHENTICATION_REQUIRED', 'description' => 'This is similar to 401 Unauthorized but authentication is needed to be done by a proxy.'],
            ['id' => ResponseTypes::REQUEST_TIMEOUT->value, 'name' => 'Request Timeout', 'code' => 'REQUEST_TIMEOUT', 'description' => 'This response is sent on an idle connection by some servers, even without any previous request by the client. It means that the server would like to shut down this unused connection.'],
            ['id' => ResponseTypes::CONFLICT->value, 'name' => 'Conflict', 'code' => 'CONFLICT', 'description' => 'This response is sent when a request conflicts with the current state of the server.'],
            ['id' => ResponseTypes::GONE->value, 'name' => 'Gone', 'code' => 'GONE', 'description' => 'This response corresponds to a situation where the requested content has been permanently deleted from server, with no forwarding address.'],
            ['id' => ResponseTypes::LENGTH_REQUIRED->value, 'name' => 'Length Required', 'code' => 'LENGTH_REQUIRED', 'description' => 'Server rejected the request because the Content-Length header field is not defined and the server requires it.'],
            ['id' => ResponseTypes::PRECONDITION_FAILED->value, 'name' => 'Precondition Failed', 'code' => 'PRECONDITION_FAILED', 'description' => 'The client has indicated preconditions in its headers which the server does not meet.'],
            ['id' => ResponseTypes::PAYLOAD_TOO_LARGE->value, 'name' => 'Payload Too Large', 'code' => 'PAYLOAD_TOO_LARGE', 'description' => 'Request entity is larger than limits defined by server. The server might close the connection or return a Retry-After header field.'],
            ['id' => ResponseTypes::URI_TOO_LONG->value, 'name' => 'URI Too Long', 'code' => 'URI_TOO_LONG', 'description' => 'The URI requested by the client is longer than the server is willing to interpret.'],
            ['id' => ResponseTypes::UNSUPPORTED_MEDIA_TYPE->value, 'name' => 'Unsupported Media Type', 'code' => 'UNSUPPORTED_MEDIA_TYPE', 'description' => 'The media format of the requested data is not supported by the server, so the server is rejecting the request.'],
            ['id' => ResponseTypes::RANGE_NOT_SATISFIABLE->value, 'name' => 'Range Not Satisfiable', 'code' => 'RANGE_NOT_SATISFIABLE', 'description' => 'The range specified by the Range header field in the request cannot be fulfilled.'],
            ['id' => ResponseTypes::EXPECTATION_FAILED->value, 'name' => 'Expectation Failed', 'code' => 'EXPECTATION_FAILED', 'description' => 'This response code means the expectation indicated by the Expect request header field cannot be met by the server.'],
            ['id' => ResponseTypes::MISMATCHED_DEPENDENCIES->value, 'name' => 'Mismatched Dependencies', 'code' => 'MISMATCHED_DEPENDENCIES', 'description' => 'The request was well-formed but was unable to be followed due to semantic errors.'],
            ['id' => ResponseTypes::LOCKED->value, 'name' => 'Locked', 'code' => 'LOCKED', 'description' => 'The resource that is being accessed is locked.'],
            ['id' => ResponseTypes::FAILED_DEPENDENCY->value, 'name' => 'Failed Dependency', 'code' => 'FAILED_DEPENDENCY', 'description' => 'The request failed due to failure of a previous request.'],
            ['id' => ResponseTypes::TOO_EARLY->value, 'name' => 'Too Early', 'code' => 'TOO_EARLY', 'description' => 'Indicates that the server is unwilling to risk processing a request that might be replayed.'],
            ['id' => ResponseTypes::UPGRADE_REQUIRED->value, 'name' => 'Upgrade Required', 'code' => 'UPGRADE_REQUIRED', 'description' => 'The server refuses to perform the request using the current protocol but might be willing to do so after the client upgrades to a different protocol.'],
            ['id' => ResponseTypes::PRECONDITION_REQUIRED->value, 'name' => 'Precondition Required', 'code' => 'PRECONDITION_REQUIRED', 'description' => 'The origin server requires the request to be conditional. This response is intended to prevent the "lost update" problem.'],
            ['id' => ResponseTypes::TOO_MANY_REQUESTS->value, 'name' => 'Too Many Requests', 'code' => 'TOO_MANY_REQUESTS', 'description' => 'The user has sent too many requests in a given amount of time ("rate limiting").'],
            ['id' => ResponseTypes::REQUEST_HEADER_FIELDS_TOO_LARGE->value, 'name' => 'Request Header Fields Too Large', 'code' => 'REQUEST_HEADER_FIELDS_TOO_LARGE', 'description' => 'The server is unwilling to process the request because its header fields are too large.'],
            ['id' => ResponseTypes::UNAVAILABLE_FOR_LEGAL_REASONS->value, 'name' => 'Unavailable For Legal Reasons', 'code' => 'UNAVAILABLE_FOR_LEGAL_REASONS', 'description' => 'The user agent requested a resource that cannot legally be provided, such as a web page censored by a government.'],
            
            // Server Error 5xx
            ['id' => ResponseTypes::INTERNAL_SERVER_ERROR->value, 'name' => 'Internal Server Error', 'code' => 'INTERNAL_SERVER_ERROR', 'description' => 'The server has encountered a situation it does not know how to handle.'],
            ['id' => ResponseTypes::NOT_IMPLEMENTED->value, 'name' => 'Not Implemented', 'code' => 'NOT_IMPLEMENTED', 'description' => 'The request method is not supported by the server and cannot be handled.'],
            ['id' => ResponseTypes::BAD_GATEWAY->value, 'name' => 'Bad Gateway', 'code' => 'BAD_GATEWAY', 'description' => 'This error response means that the server, while working as a gateway to get a response needed to handle the request, got an invalid response.'],
            ['id' => ResponseTypes::SERVICE_UNAVAILABLE->value, 'name' => 'Service Unavailable', 'code' => 'SERVICE_UNAVAILABLE', 'description' => 'The server is not ready to handle the request. Common causes are a server that is down for maintenance or that is overloaded.'],
            ['id' => ResponseTypes::GATEWAY_TIMEOUT->value, 'name' => 'Gateway Timeout', 'code' => 'GATEWAY_TIMEOUT', 'description' => 'This error response is given when the server is acting as a gateway and cannot get a response in time.'],
            ['id' => ResponseTypes::HTTP_VERSION_NOT_SUPPORTED->value, 'name' => 'HTTP Version Not Supported', 'code' => 'HTTP_VERSION_NOT_SUPPORTED', 'description' => 'The HTTP version used in the request is not supported by the server.'],
            ['id' => ResponseTypes::VARIANT_ALSO_NEGOTIATES->value, 'name' => 'Variant Also Negotiates', 'code' => 'VARIANT_ALSO_NEGOTIATES', 'description' => 'The server has an internal configuration error: the chosen variant resource is configured to engage in transparent content negotiation itself.'],
            ['id' => ResponseTypes::INSUFFICIENT_STORAGE->value, 'name' => 'Insufficient Storage', 'code' => 'INSUFFICIENT_STORAGE', 'description' => 'The method could not be performed on the resource because the server is unable to store the representation needed to successfully complete the request.'],
            ['id' => ResponseTypes::LOOP_DETECTED->value, 'name' => 'Loop Detected', 'code' => 'LOOP_DETECTED', 'description' => 'The server detected an infinite loop while processing a request.'],
            ['id' => ResponseTypes::NOT_EXTENDED->value, 'name' => 'Not Extended', 'code' => 'NOT_EXTENDED', 'description' => 'Further extensions to the request are required for the server to fulfill it.'],
            ['id' => ResponseTypes::NETWORK_AUTHENTICATION_REQUIRED->value, 'name' => 'Network Authentication Required', 'code' => 'NETWORK_AUTHENTICATION_REQUIRED', 'description' => 'Indicates that the client needs to authenticate to gain network access.'],
        ];

        foreach ($types as $type) {
            ResponseType::updateOrCreate(['id' => $type['id']], $type);
        }
    }
}
