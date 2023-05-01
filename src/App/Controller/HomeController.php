<?php
/**
 * @author Florent HAZARD <f.hazard@sowapps.com>
 */

namespace App\Controller;

use Orpheus\Config\Config;
use Orpheus\Exception\UserException;
use Orpheus\File\UploadedFile;
use Orpheus\InputController\HttpController\HttpRequest;
use Orpheus\InputController\HttpController\HttpResponse;

class HomeController extends AbstractHttpController {
	
	/**
	 * Controller declaration
	 *
	 * @param HttpRequest $request The input HTTP request
	 * @return HttpResponse The output HTTP response
	 */
	public function run($request): HttpResponse {
		if( !$this->isAuthenticated() ) {
			header('WWW-Authenticate: Basic realm="Authentication required"');
			header('HTTP/1.0 401 Unauthorized');
			return new HttpResponse();
		}
		
		return $this->uploadForm($request);
	}
	
	protected function isAuthenticated(): bool {
		// We keep value in session but browser may keep http authentication and resend it automatically
		if( $_SESSION['authenticated'] ?? false ) {
			return true;
		}
		$user = $_SERVER['PHP_AUTH_USER'] ?? null;
		$password = $_SERVER['PHP_AUTH_PW'] ?? null;
		
		if( !$user ) {
			return false;
		}
		
		if( $user === $this->getAuthUser() && $password === $this->getAuthPassword() ) {
			$_SESSION['authenticated'] = true;
			return true;
		}
		return false;
	}
	
	protected function getAuthUser(): string {
		if( defined('APP_AUTH_USER') ) {
			// Defined constant in instance.php to avoid git repository
			return APP_AUTH_USER;
		}
		
		return Config::get('auth_user');
	}
	
	protected function getAuthPassword(): string {
		if( defined('APP_AUTH_PASSWORD') ) {
			// Defined constant in instance.php to avoid git repository
			return APP_AUTH_PASSWORD;
		}
		
		return Config::get('auth_password');
	}
	
	protected function uploadForm(HttpRequest $request): HttpResponse {
		try {
			if( $request->isPOST() ) {
				$this->validateFormToken($request);
				
				$files = UploadedFile::load('file');
				$storePath = $this->getStorePath();
				
				foreach( $files as $file ) {
					/** @var UploadedFile $file */
					$file->moveTo($storePath . '/' . $this->sanitizeFileName($file->getFileName()));
				}
				
				$this->storeSuccess('upload', 'uploadSuccess');
				return $this->redirectToSelf();
			}
		} catch( UserException $e ) {
			reportError($e);
		}
		
		$this->consumeSuccess('upload');
		
		$uploadMaxSizeHuman = ini_get('upload_max_filesize');
		$uploadMaxSizeBytes = parseHumanSize($uploadMaxSizeHuman . 'b');
		
		return $this->renderHtml('app/upload', [
			'uploadMaxSizeBytes' => $uploadMaxSizeBytes,
			'uploadMaxSizeHuman' => $uploadMaxSizeHuman,
			'files'              => $this->getFiles(),
		]);
	}
	
	protected function getStorePath(): string {
		$path = Config::get('upload_path', 'store');
		if( $path[0] !== '/' ) {
			//			$path = pathOf($path);
			$path = INSTANCE_PATH . '/' . $path;
			if( !file_exists($path) ) {
				mkdir($path, 0777, true);
			}
		}
		return $path;
	}
	
	protected function sanitizeFileName(string $fileName): string {
		$name = convertSpecialChars($fileName);
		$name = str_replace(' ', '-', $name); // Replaces all spaces with hyphens.
		$name = preg_replace('/[^A-Za-z0-9\-\._]/', '', $name); // Removes special chars
		return $name;
	}
	
	protected function getFiles(): array {
		$storePath = $this->getStorePath();
		$fileNames = cleanscandir($storePath);
		$uploadUri = $this->getUploadUri();
		return array_map(function ($fileName) use ($storePath, $uploadUri) {
			$path = $storePath . '/' . $fileName;
			$size = filesize($path);
			return (object)[
				'label' => $fileName,
				'link'  => $uploadUri . '/' . $fileName,
				'path'  => $path,
				'size'  => formatHumanSize($size, 1024, true, 1024),
			];
		}, $fileNames);
	}
	
	protected function getUploadUri(): string {
		$uploadUri = Config::get('upload_uri');
		$uploadUri = str_replace('{request_base_uri}', WEB_ROOT, $uploadUri);
		return $uploadUri;
	}
	
}


