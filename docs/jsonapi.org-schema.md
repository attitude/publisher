# JSONAPI.org Schema

- **Refference:** http://jsonapi.org/
- **Status:** development (unstable)

## JSON API by Publisher

Here's a example of (hopefully) compatible JSON document with the JSON API produced by Publisher.


```json
{
	// Current resource
	
	"post" : {
		"id": ...,
		"type": post,
		...,
		
		// Directly linked resources
		
		"links": {
			"author": 123,
			"comments": [1, 2, 3],
			...,
			...
		}
	},
	
	// Self-discovery links relevant to resource
	
	"links": {
		posts: {
			"href": "https://api/posts",
			"type": "post"
		},
		"posts.author": {
	        "href": "http://api/people/{posts.author}",
	        "type": "people"
	    },
	    "posts.comments": {
	        "href": "http://api/comments/{posts.comments}",
	        "type": "comments"
	    },
	    ...
	},
	
	// Resources somehow linked to resource
	
	"linked": {
		"website": {
			...
		},
		"eshop": {
			...
		},
		...
	}
}
```