/*
* Model for adding comments/geodata
*/
CommentAdd = Backbone.Model.extend({
	
	initialize: function(){
          console.log("New CommentAdd-Model added");
    },
     
	urlRoot: '/geodata/app'
});

/*
* Model for comments/geodata with spatial reference
*/
CommentsWithSpatial = Backbone.Model.extend({
	urlRoot: '/geodata/list'
});

/*
* Collection of CommentsWithSpatial-models
*/
CommentsWSList = Backbone.Collection.extend({
	model: CommentsWithSpatial
});

/*
* Model for comments/geodata without spatial reference
*/
CommentsNoSpatial = Backbone.Model.extend({
	urlRoot: '/geodata/list/junk'
});

/*
* Collection of CommentsNo-models
*/
CommentsWSList = Backbone.Collection.extend({
	model: CommentsNoSpatial
});

/*
* Model for comments of a geodata
*/
CommentsOfGeodata = Backbone.Model.extend({
	urlRoot: '/geodata/id/comments/'
});