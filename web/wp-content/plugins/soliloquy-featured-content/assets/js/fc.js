/* ==========================================================
 * fc.js
 * http://soliloquywp.com/
 * ==========================================================
 * Copyright 2014 Thomas Griffin.
 *
 * Licensed under the GPL License, Version 2.0 or later (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================== */
;(function($){
    $(function(){
        // Flag for determing if the user has selected anything or not yet since page load.
        var chosen_term = chosen_post = false;

        // Initialize JS.
        soliloquyFcInit();

        // Initialize JS when the Featured Content slider type is selected.
        $(document).on('soliloquySliderType', function(e, data){
            if ( data.type && 'fc' == data.type ) {
                soliloquyFcInit();
            }
        });

        // Callback function to initialize the Featured Content JS.
        function soliloquyFcInit() {
            // Initialize chosen on specific select boxes.
            //$('.soliloquy-fc-chosen').chosen();

            // Run conditionals.
            soliloquyFcConditionals();

            // Show/hide the inclusion groups (and even the inclusion step itself if certain conditions are met).
            var soliloquy_fc_post_type_val = $('#soliloquy-config-fc-post-type').val();
            if ( soliloquy_fc_post_type_val ) {
                $('#soliloquy-config-fc-inc-ex option:selected').trigger('change').trigger('chosen:updated');
                
                // Conditionally refresh the posts selection box.
                if ( ! chosen_post ) {
                    chosen_post = true;
                    soliloquyFcRefreshPostsCond(soliloquy_fc_post_type_val);
                } else {
                    soliloquyFcRefreshPosts(soliloquy_fc_post_type_val);
                }

                // Conditionally refresh the terms selection box.
                if ( ! chosen_term ) {
                    chosen_term = true;
                    soliloquyFcRefreshTermsCond(soliloquy_fc_post_type_val);
                } else {
                    soliloquyFcRefreshTerms(soliloquy_fc_post_type_val);
                }
            } else {
                // Default to "post" and trigger events to make sure Chosen functions correctly.
                $('#soliloquy-config-fc-post-type option[value="post"]').attr('selected', 'selected').trigger('change').trigger('chosen:updated');

                // Conditionally refresh the posts selection box.
                if ( ! chosen_post ) {
                    chosen_post = true;
                    soliloquyFcRefreshPostsCond(soliloquy_fc_post_type_val);
                }

                // Conditionally refresh the terms selection box.
                if ( ! chosen_term ) {
                    chosen_term = true;
                    soliloquyFcRefreshTermsCond(soliloquy_fc_post_type_val);
                }
            }

            // Use ajax to show/hide terms related to the currently selected post type(s) on value change.
            $('#soliloquy-config-fc-post-type').chosen().change(function(){
                var post_type_val = $('#soliloquy-config-fc-post-type').val();
                if ( post_type_val ) {
                    soliloquyFcRefreshPosts(post_type_val);
                    soliloquyFcRefreshTerms(post_type_val);
                }
            });
        }

        // Callback function to show/hide conditional elements.
        function soliloquyFcConditionals() {
            // Show/hide post title linking if the post title is to be output.
            if ( $('#soliloquy-config-fc-post-title').is(':checked') ) {
                $('#soliloquy-config-fc-post-title-link-box').show();
            } else {
                $('#soliloquy-config-fc-post-title-link-box').hide();
            }
            $(document).on('change', '#soliloquy-config-fc-post-title', function(){
                if ( $(this).is(':checked') )
                    $('#soliloquy-config-fc-post-title-link-box').fadeIn();
                else
                    $('#soliloquy-config-fc-post-title-link-box').fadeOut();
            });

            // Show/hide content length and ellipses box if content is to be output.
            if ( 'post_content' == $('#soliloquy-config-fc-content-type').val() ) {
                $('#soliloquy-config-fc-content-length-box, #soliloquy-config-fc-content-ellipses-box, #soliloquy-content-fc-content-html').show();
            } else {
                $('#soliloquy-config-fc-content-length-box, #soliloquy-config-fc-content-ellipses-box, #soliloquy-content-fc-content-html').hide();
            }
            $(document).on('change', '#soliloquy-config-fc-content-type', function(){
                if ( 'post_content' == $(this).val() )
                    $('#soliloquy-config-fc-content-length-box, #soliloquy-config-fc-content-ellipses-box, #soliloquy-content-fc-content-html').fadeIn();
                else
                    $('#soliloquy-config-fc-content-length-box, #soliloquy-config-fc-content-ellipses-box, #soliloquy-content-fc-content-html').fadeOut();
            });

            // Show/hide read more text if read more box is checked.
            if ( $('#soliloquy-config-fc-read-more').is(':checked') ) {
                $('#soliloquy-config-fc-read-more-text-box').show();
            } else {
                $('#soliloquy-config-fc-read-more-text-box').hide();
            }
            $(document).on('change', '#soliloquy-config-fc-read-more', function(){
                if ( $(this).is(':checked') )
                    $('#soliloquy-config-fc-read-more-text-box').fadeIn();
                else
                    $('#soliloquy-config-fc-read-more-text-box').fadeOut();
            });
        }

        // Callback function to process refreshing of terms.
        function soliloquyFcRefreshTerms(posttype){
            if ( ! chosen_term ) {
                chosen_term = true;
                return;
            }

            // Set the posttype array if none have been selected.
            if ( ! posttype ) {
                posttype = ['post'];
            }

            // Output the loading icon.
            $('#soliloquy_config_fc_terms_chosen').after('<span class="spinner soliloquy-spinner" style="display:inline-block;"></span>');

            var opts = {
                type: 'post',
                url: soliloquy_metabox.ajax,
                async: true,
                cache: false,
                dataType: 'json',
                data: {
                    action:    'soliloquy_fc_refresh_terms',
                    nonce:     soliloquy_fc_metabox.term_nonce,
                    post_type: posttype,
                    post_id:   soliloquy_metabox.id
                },
                success: function(json){
                    if ( json && json.error ) {
                        $('.soliloquy-spinner').remove();
                        $('#soliloquy-config-fc-terms option:selected').removeAttr('selected').trigger('change').trigger('chosen:updated');
                        $('#soliloquy-config-fc-terms-box').fadeOut();
                        $('#soliloquy-config-fc-terms-relation-box').fadeOut();
                    } else {
                        $('#soliloquy-config-fc-terms-box').fadeIn('normal', function() {
                            $('#soliloquy-config-fc-terms-relation-box').fadeIn();
                            $('.soliloquy-spinner').remove();
                            $('#soliloquy-config-fc-terms').empty().append(json).trigger('change').trigger('chosen:updated');
                        });
                    }
                },
                error: function(xhr){
                    $('.soliloquy-spinner').remove();
                }
            }
            $.ajax(opts);
        }

        function soliloquyFcRefreshTermsCond(posttype){
            var opts = {
                type: 'post',
                url: soliloquy_metabox.ajax,
                async: true,
                cache: false,
                dataType: 'json',
                data: {
                    action:    'soliloquy_fc_refresh_terms',
                    nonce:     soliloquy_fc_metabox.term_nonce,
                    post_type: posttype,
                    post_id:   soliloquy_metabox.id
                },
                success: function(json){
                    // We only need to handle errors if no taxonomy is shared between the post types.
                    if ( json && json.error ) {
                        $('#soliloquy-config-fc-terms-box').hide();
                        $('#soliloquy-config-fc-terms-relation-box').hide();
                        return;
                    } else {
                        /** Grab all currently chosen items and repopulate them */
                        $('#soliloquy-config-fc-terms-box').show();
                        $('#soliloquy-config-fc-terms-relation-box').show();
                        $('#soliloquy-config-fc-terms').empty().append(json);
                        $('#soliloquy_fc_terms_chzn .chzn-results li.result-selected').each(function(){
                            var el = $(this);
                            $('#soliloquy-config-fc-terms option').each(function(){
                                if ( $(this).text() == el.text() )
                                    $(this).attr('selected', 'selected');
                            });
                        });
                        $('#soliloquy-config-fc-terms').trigger('change').trigger('chosen:updated');
                    }
                },
                error: function(xhr){
                }
            }
            $.ajax(opts);
        }

        function soliloquyFcRefreshTermsCondMulti(posttype){
            var opts = {
                type: 'post',
                url: soliloquy_metabox.ajax,
                async: true,
                cache: false,
                dataType: 'json',
                data: {
                    action:    'soliloquy_fc_refresh_terms',
                    nonce:     soliloquy_fc_metabox.term_nonce,
                    post_type: posttype,
                    post_id:   soliloquy_metabox.id
                },
                success: function(json){
                    if ( json && json.error ) {
                        $('.soliloquy-spinner').remove();
                        $('#soliloquy-config-fc-terms option:selected').removeAttr('selected').trigger('change').trigger('chosen:updated');
                        $('#soliloquy-config-fc-terms-box').fadeOut();
                        $('#soliloquy-config-fc-terms-relation-box').fadeOut();
                    } else {
                        $('#soliloquy-config-fc-terms-box').fadeIn('normal', function(){
                            $('#soliloquy-config-fc-terms-relation-box').fadeIn();
                            $('.soliloquy-spinner').remove();
                            $('#soliloquy-config-fc-terms').empty().append(json).trigger('change').trigger('chosen:updated');
                        });
                    }
                },
                error: function(xhr){
                }
            }
            $.ajax(opts);
        }

        function soliloquyFcRefreshPosts(posttype){
            if ( ! chosen_post ) {
                chosen_post = true;
                return;
            }

            // Set type to post in array if there is none selected.
            if ( ! posttype ) {
                posttype = ['post'];
            }

            // Output the loading icon.
            $('#soliloquy_config_fc_post_type_chosen').after('<span class="spinner soliloquy-spinner" style="display:inline-block;"></span>');

            var opts = {
                type: 'post',
                url: soliloquy_metabox.ajax,
                async: true,
                cache: false,
                dataType: 'json',
                data: {
                    action:    'soliloquy_fc_refresh_posts',
                    nonce:     soliloquy_fc_metabox.refresh_nonce,
                    post_type: posttype,
                    post_id:   soliloquy_metabox.id
                },
                success: function(json){
                    if ( json && json.error ) {
                        $('.soliloquy-spinner').remove();
                        $('#soliloquy-config-fc-inc-ex option:selected').removeAttr('selected').trigger('change').trigger('chosen:updated');
                        $('#soliloquy-config-fc-inc-ex-box').fadeOut();
                    } else {
                        $('#soliloquy-config-fc-inc-ex-box').fadeIn('normal', function(){
                            $('.soliloquy-spinner').remove();
                            $('#soliloquy-config-fc-inc-ex').empty().append(json).trigger('change').trigger('chosen:updated');
                        });
                    }
                },
                error: function(xhr){
                    $('.soliloquy-spinner').remove();
                }
            }

            $.ajax(opts);
        }

        function soliloquyFcRefreshPostsCond(posttype){
            var data = {
                    action:    'soliloquy_fc_refresh_posts',
                    nonce:     soliloquy_fc_metabox.refresh_nonce,
                    post_type: posttype,
                    post_id:   soliloquy_metabox.id
                };
            $.post(soliloquy_metabox.ajax, data, function(json){
                // We only need to update the list of posts to chose from based on the user selection on page load.
                if ( json && json.error ) {
                    $('#soliloquy-config-fc-inc-ex-box').hide();
                    return;
                } else {
                    // Grab all currently chosen items and repopulate them.
                    $('#soliloquy-config-fc-inc-ex-box').show();
                    $('#soliloquy-config-fc-inc-ex').empty().append(json);
                    $('#soliloquy_fc_include_exclude_chzn .chzn-results li.result-selected').each(function(){
                        var el = $(this);
                        $('#soliloquy-config-fc-inc-ex option').each(function(){
                            if ( $(this).text() == el.text() )
                                $(this).attr('selected', 'selected');
                        });
                    });
                    $('#soliloquy-config-fc-inc-ex').trigger('change').trigger('chosen:updated');
                }
            }, 'json');
        }
    });
}(jQuery));