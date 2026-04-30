/**
 * Mochin Theme - Screenshot Generation for Social Media
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        const $button = $('#mochin-generate-screenshot');
        const $status = $('#mochin-screenshot-status');

        if (!$button.length) {
            return;
        }

        $button.on('click', function(e) {
            e.preventDefault();
            generateScreenshot();
        });

        function generateScreenshot() {
            $button.prop('disabled', true).text('Generating...');
            $status.html('<span style="color: #0073aa;">Processing...</span>');

            // Get the post content area
            const element = document.querySelector('.post-content') || document.querySelector('article');

            if (!element) {
                showError('Could not find post content to capture');
                return;
            }

            // Create a wrapper for better social media formatting
            const wrapper = createSocialWrapper(element);

            // Capture the screenshot
            html2canvas(wrapper, {
                backgroundColor: '#ffffff',
                scale: 2,
                width: 1200,
                height: 630,
                windowWidth: 1200,
                windowHeight: 630,
                logging: false,
                useCORS: true,
                allowTaint: true
            }).then(function(canvas) {
                // Remove the temporary wrapper
                document.body.removeChild(wrapper);

                // Convert canvas to base64
                const imageData = canvas.toDataURL('image/png');

                // Send to server
                saveScreenshot(imageData);
            }).catch(function(error) {
                document.body.removeChild(wrapper);
                showError('Failed to generate screenshot: ' + error.message);
            });
        }

        function createSocialWrapper(sourceElement) {
            const wrapper = document.createElement('div');
            wrapper.style.cssText = `
                position: fixed;
                top: -10000px;
                left: -10000px;
                width: 1200px;
                height: 630px;
                background: white;
                padding: 60px;
                box-sizing: border-box;
                font-family: system-ui, -apple-system, sans-serif;
                overflow: hidden;
            `;

            // Get post title
            const titleElement = document.querySelector('.post-header h1, .page-header h1, h1');
            const title = titleElement ? titleElement.textContent : 'Blog Post';

            // Get site name
            const siteName = document.querySelector('.site-branding a') ? 
                document.querySelector('.site-branding a').textContent : 
                'Blog';

            // Create content
            wrapper.innerHTML = `
                <div style="height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="font-size: 18px; color: #666; margin-bottom: 20px;">${siteName}</div>
                        <h1 style="font-size: 48px; line-height: 1.2; margin: 0; color: #111; font-weight: 700; max-height: 300px; overflow: hidden;">
                            ${title}
                        </h1>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: space-between; border-top: 3px solid #111; padding-top: 20px;">
                        <div style="font-size: 20px; color: #666;">
                            ${window.location.hostname}
                        </div>
                        <div style="width: 60px; height: 60px; background: #111; border-radius: 8px;"></div>
                    </div>
                </div>
            `;

            document.body.appendChild(wrapper);
            return wrapper;
        }

        function saveScreenshot(imageData) {
            $.ajax({
                url: mochinScreenshot.ajaxurl,
                type: 'POST',
                data: {
                    action: 'mochin_save_screenshot',
                    nonce: mochinScreenshot.nonce,
                    post_id: mochinScreenshot.post_id,
                    image: imageData
                },
                success: function(response) {
                    if (response.success) {
                        $status.html('<span style="color: #46b450;">✓ Screenshot saved as featured image!</span>');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        showError(response.data || 'Failed to save screenshot');
                    }
                },
                error: function(xhr, status, error) {
                    showError('AJAX error: ' + error);
                },
                complete: function() {
                    $button.prop('disabled', false).text('Generate Social Media Screenshot');
                }
            });
        }

        function showError(message) {
            $status.html('<span style="color: #dc3232;">✗ ' + message + '</span>');
            $button.prop('disabled', false).text('Generate Social Media Screenshot');
            console.error('Screenshot error:', message);
        }
    });

})(jQuery);
