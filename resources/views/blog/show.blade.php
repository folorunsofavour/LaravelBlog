<x-app-layout>
    <!-- <div class="w-4/5 m-auto flex justify-between items-center">
        <div class="py-10">
            <h1 class="text-6xl">
                {{ $blog->title }}
            </h1>
        </div>

        <div class="pt-2 text-gray-500">
            By <span class="font-bold italic text-gray-800">{{ $blog->user->name }}</span>, Created on {{ date('jS M Y', strtotime($blog->updated_at)) }}
        </div>
    </div> -->

    <div  class="pt-10 w-3/5 m-auto">
        <img src="{{ $blog->image_path }}" alt="">
    </div>

    <div class="w-3/5 m-auto text-left">
        <div class="py-2">
            <h1 class="text-4xl">
                {{ $blog->title }}
            </h1>
        </div>
        
        <span class="text-gray-500">
            By <span class="font-bold italic text-gray-800">{{ $blog->user->name }}</span>, Created on {{ date('jS M Y', strtotime($blog->updated_at)) }}, {{$blog->updated_at->diffForHumans()}}
        </span>
    </div>

    <div class="w-3/5 m-auto pt-2">
        <p class="text-xl text-gray-700 pt-8 pb-10 leading-8 font-light">
            {{ $blog->description }}
        </p>
    </div>

    <!-- comments section-->
    <div class="bg-gray-100 p-4 rounded-lg shadow-md">
        <!-- Comments Header -->
        <h2 class="text-xl font-semibold mb-4">Comments</h2>

        <!-- Add New Comment Form -->
        <form id="commentForm">
            @csrf
            <div class="flex mb-4 py-5">
                <!-- Comment Input with Button -->
                <input type="text" name="blog_id" id="blog_id" value="{{ $blog->id}}" hidden>
                <div class="flex-1 relative">
                    <textarea name="comment" id="comment" rows="2" placeholder="Add a comment..." class="w-full rounded-lg shadow-sm p-2 border border-gray-300 focus:outline-none focus:border-blue-500"></textarea>
                    <!-- Post Comment Button -->
                    <button id="commentButton" class="absolute top-0 right-0 mt-2 mr-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline">Comment</button>
                </div>
            </div>
        </form>

        <!-- Individual Comment -->
        
        <div class="commentContainer">
        @if(count($blog->comments)>0)
            @foreach($blog->comments as $comment)
                <div class="flex mb-2">
                    <!-- Avatar -->
                    <div class="flex-shrink-0 mr-4">
                        <img src="https://via.placeholder.com/50" alt="Avatar" class="rounded-full h-12 w-12">
                    </div>
                    <!-- Comment Content -->
                    <div>
                        <!-- Comment Author -->
                        <div class="font-semibold mb-1">{{$comment->user->name}}</div>
                        <!-- Comment Text -->
                        <div class="text-gray-800">{{$comment->comment}}</div>
                    </div>
                </div>

                <!-- Reply Form for the Comment Reply-->
                <!-- <form id="replyForm"> -->
                    <!-- @csrf -->
                    <div class=" ml-16 flex mb-2">
                        <!-- Avatar -->
                        <div class="flex-shrink-0 mr-4">
                            <img src="https://via.placeholder.com/50" alt="Avatar" class="rounded-full h-10 w-10">
                        </div>
                        <input type="text" name="post_id" id="postInput_{{$comment->id}}" value="{{ $blog->id }}" hidden>
                        <input type="text" name="parent_id" id="parentInput_{{$comment->id}}" value="{{ $comment->id }}" hidden>
                        <!-- Reply Input with Button -->
                        <div class="flex-1 relative">
                            <textarea name="reply" id="replyTextarea_{{$comment->id}}" rows="1" placeholder="Reply..." class="w-full rounded-lg shadow-sm p-2 border border-gray-300 focus:outline-none focus:border-blue-500"></textarea>
                            <!-- Post Reply Button -->
                            <button id="replyButton_{{$comment->id}}" class="absolute top-0 right-0 mt-2 mr-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1 px-3 rounded-lg focus:outline-none focus:shadow-outline">Reply</button>
                        </div>
                    </div>
                <!-- </form> -->

                <!-- Nested Replies for the Comment -->
                @if(count($comment->replies)>0)
                    <!-- <div class="ml-16"> -->
                        <!-- Individual Reply -->
                        <div class="replyContainer_{{$comment->id}}">
                            @foreach($comment->replies as $reply)
                            
                                <div class="flex mb-2 ml-16">
                                    <!-- Avatar -->
                                    <div class="flex-shrink-0 mr-4">
                                        <img src="https://via.placeholder.com/50" alt="Avatar" class="rounded-full h-10 w-10">
                                    </div>
                                    <!-- Reply Content -->
                                    <div>
                                        <!-- Reply Author -->
                                        <div class="font-semibold mb-1">{{$reply->user->name}}</div>
                                        <!-- Reply Text -->
                                        <div class="text-gray-800">{{$reply->comment}}</div>
                                    </div>
                                </div>
                            @endforeach  
                        </div>
                        <!-- Add more replies here if needed -->
                    <!-- </div> -->
                @endif
            @endforeach 
             
        @else
            <div id="noComment" class="font-semibold italic mb-1">No Comment Yet</div>
        @endif
        </div>
        <!-- Add more comments here if needed -->
    </div>


    <!-- AJAX -->
    <script>
    // Handle form submission with AJAX
        $('#commentButton').click(function(event) {
            event.preventDefault(); // Prevent the form from submitting normally

            // Get form values
            var blog_id = $('#blog_id').val();
            var comment = $('#comment').val();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Get the form data
            var formData = {
                blog_idData: blog_id,
                commentData: comment 
            };

            // Send an AJAX request to the Laravel route
            $.ajax({
                url: '/comment',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: formData,
                success: function(response) {
                    console.log('AJAX request successful!');
                    console.log(response);
                    // If the request is successful, append the new comment to the DOM
                    // Use response.comment.id as the unique identifier for each reply form
                    // var replyFormId = 'replyForm_' + response.comment.id;
                    var replyTextareaId = 'replyTextarea_' + response.comment.id;
                    var replyButtonId = 'replyButton_' + response.comment.id;
                    var postInputId = 'postInput_' + response.comment.id;
                    var parentInputId = 'parentInput_' + response.comment.id;
                    var replyContainerId = 'replyContainer_' + response.comment.id;

                    // console.log('replyTextarea_' +replyTextareaId);
                    // console.log('replyButton_' +replyButtonId);
                    // console.log('postInput_' + postInputId);
                    // console.log('parentInput_' +parentInputId);
                    // console.log('replyContainer_' +replyContainerId);

                    // Append the reply form with the unique IDs
                    $('.commentContainer').append(
                        '<div class="flex mb-2">' +
                            '<div class="flex-shrink-0 mr-4">' +
                                '<img src="https://via.placeholder.com/50" alt="Avatar" class="rounded-full h-12 w-12">' +
                            '</div>' +
                            '<div>' +
                                '<div class="font-semibold mb-1">' + response.comment.user.name + '</div>' +
                                '<div class="text-gray-800">'+ response.comment.comment +'</div>' +
                            '</div>' +
                        '</div>' +
                        
                        '<div class="' + replyContainerId + '">' +
                            '<div class=" ml-16 flex mb-2">' +
                                '<div class="flex-shrink-0 mr-4">' +
                                    '<img src="https://via.placeholder.com/50" alt="Avatar" class="rounded-full h-10 w-10">' +
                                '</div>' +
                                '<input type="text" name="post_id" id="' + postInputId + '" value="' + response.comment.blog_id + '" hidden>' +
                                '<input type="text" name="parent_id" id="' + parentInputId + '" value="' + response.comment.id + '" hidden>' +
                                '<div class="flex-1 relative">' +
                                    '<textarea name="reply" id="' + replyTextareaId + '" rows="1" placeholder="Reply..." class="w-full rounded-lg shadow-sm p-2 border border-gray-300 focus:outline-none focus:border-blue-500"></textarea>' +
                                    '<button id="' + replyButtonId + '" class="absolute top-0 right-0 mt-2 mr-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1 px-3 rounded-lg focus:outline-none focus:shadow-outline">Reply</button>' +
                                '</div>' +
                            '</div>' +
                        '</div>'
                        
                    );

                    // Clear the comment input field
                    $('#comment').val('');
                    $('#noComment').remove();
                },
                error: function(xhr) {
                    // Handle errors
                    console.log(xhr.responseText);
                }
            });
        });


        // Event delegation for dynamically added reply forms
        $('.commentContainer').on('click', '[id^="replyButton_"]', function() {
            // Extract the comment ID from the button's ID
            var commentId = $(this).attr('id').split('_')[1];
            
            // Get the value of the corresponding textarea
            var replyText = $('#replyTextarea_' + commentId).val();
            var postId = $('#postInput_' + commentId).val();
            var parentId = $('#parentInput_' + commentId).val();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // gest IDs
            var replyContainer = 'replyContainer_' + commentId;
            var replyTextRemove = 'replyTextarea_' + commentId;

            // console.log('replyTextareaID' +replyTextRemove);
            // console.log('replyButton' +commentId);
            // console.log('postInput' + postId);
            // console.log('parentInput' +parentId);
            // console.log('replyContainer' +replyContainer);

            // Get the form data
            var formData = {
                post_idData: postId, 
                replyData: replyText, 
                parent_idData: parentId 
            };

            // Send an AJAX request to the Laravel route
            $.ajax({
                url: '/reply',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: formData,
                success: function(response) {
                    console.log('AJAX request successful!');
                    console.log(response);
                    // If the request is successful, append the new comment to the DOM
                    $('.' + replyContainer).append(
                        '<div class="flex mb-2 ml-16">' +
                            '<div class="flex-shrink-0 mr-4">' +
                                '<img src="https://via.placeholder.com/50" alt="Avatar" class="rounded-full h-10 w-10">' +
                            '</div>' +
                            '<div>' +
                                '<div class="font-semibold mb-1">' + response.reply.user.name + '</div>' +
                                '<div class="text-gray-800">'+ response.reply.comment +'</div>' +
                            '</div>' +
                        '<div>'
                        );

                    // Clear the comment input field
                    $('#' + replyTextRemove).val('');
                },
                error: function(xhr) {
                    // Handle errors
                    console.log(xhr.responseText);
                }
            });
        });
    </script>



</x-app-layout>