(function() {

    var debug = false;

    var root = this;

    var EXIF = function(obj) {
        if (obj instanceof EXIF)
            return obj;
        if (!(this instanceof EXIF))
            return new EXIF(obj);
        this.EXIFwrapped = obj;
    };

    if (typeof exports !== 'undefined') {
        if (typeof module !== 'undefined' && module.exports) {
            exports = module.exports = EXIF;
        }
        exports.EXIF = EXIF;
    } else {
        root.EXIF = EXIF;
    }

    var ExifTags = EXIF.Tags = {

        // version tags
        0x9000: "ExifVersion",
        // EXIF version
        0xA000: "FlashpixVersion",
        // Flashpix format version

        // colorspace tags
        0xA001: "ColorSpace",
        // Color space information tag

        // image configuration
        0xA002: "PixelXDimension",
        // Valid width of meaningful image
        0xA003: "PixelYDimension",
        // Valid height of meaningful image
        0x9101: "ComponentsConfiguration",
        // Information about channels
        0x9102: "CompressedBitsPerPixel",
        // Compressed bits per pixel

        // user information
        0x927C: "MakerNote",
        // Any desired information written by the manufacturer
        0x9286: "UserComment",
        // Comments by user

        // related file
        0xA004: "RelatedSoundFile",
        // Name of related sound file

        // date and time
        0x9003: "DateTimeOriginal",
        // Date and time when the original image was generated
        0x9004: "DateTimeDigitized",
        // Date and time when the image was stored digitally
        0x9290: "SubsecTime",
        // Fractions of seconds for DateTime
        0x9291: "SubsecTimeOriginal",
        // Fractions of seconds for DateTimeOriginal
        0x9292: "SubsecTimeDigitized",
        // Fractions of seconds for DateTimeDigitized

        // picture-taking conditions
        0x829A: "ExposureTime",
        // Exposure time (in seconds)
        0x829D: "FNumber",
        // F number
        0x8822: "ExposureProgram",
        // Exposure program
        0x8824: "SpectralSensitivity",
        // Spectral sensitivity
        0x8827: "ISOSpeedRatings",
        // ISO speed rating
        0x8828: "OECF",
        // Optoelectric conversion factor
        0x9201: "ShutterSpeedValue",
        // Shutter speed
        0x9202: "ApertureValue",
        // Lens aperture
        0x9203: "BrightnessValue",
        // Value of brightness
        0x9204: "ExposureBias",
        // Exposure bias
        0x9205: "MaxApertureValue",
        // Smallest F number of lens
        0x9206: "SubjectDistance",
        // Distance to subject in meters
        0x9207: "MeteringMode",
        // Metering mode
        0x9208: "LightSource",
        // Kind of light source
        0x9209: "Flash",
        // Flash status
        0x9214: "SubjectArea",
        // Location and area of main subject
        0x920A: "FocalLength",
        // Focal length of the lens in mm
        0xA20B: "FlashEnergy",
        // Strobe energy in BCPS
        0xA20C: "SpatialFrequencyResponse",
        //
        0xA20E: "FocalPlaneXResolution",
        // Number of pixels in width direction per FocalPlaneResolutionUnit
        0xA20F: "FocalPlaneYResolution",
        // Number of pixels in height direction per FocalPlaneResolutionUnit
        0xA210: "FocalPlaneResolutionUnit",
        // Unit for measuring FocalPlaneXResolution and FocalPlaneYResolution
        0xA214: "SubjectLocation",
        // Location of subject in image
        0xA215: "ExposureIndex",
        // Exposure index selected on camera
        0xA217: "SensingMethod",
        // Image sensor type
        0xA300: "FileSource",
        // Image source (3 == DSC)
        0xA301: "SceneType",
        // Scene type (1 == directly photographed)
        0xA302: "CFAPattern",
        // Color filter array geometric pattern
        0xA401: "CustomRendered",
        // Special processing
        0xA402: "ExposureMode",
        // Exposure mode
        0xA403: "WhiteBalance",
        // 1 = auto white balance, 2 = manual
        0xA404: "DigitalZoomRation",
        // Digital zoom ratio
        0xA405: "FocalLengthIn35mmFilm",
        // Equivalent foacl length assuming 35mm film camera (in mm)
        0xA406: "SceneCaptureType",
        // Type of scene
        0xA407: "GainControl",
        // Degree of overall image gain adjustment
        0xA408: "Contrast",
        // Direction of contrast processing applied by camera
        0xA409: "Saturation",
        // Direction of saturation processing applied by camera
        0xA40A: "Sharpness",
        // Direction of sharpness processing applied by camera
        0xA40B: "DeviceSettingDescription",
        //
        0xA40C: "SubjectDistanceRange",
        // Distance to subject

        // other tags
        0xA005: "InteroperabilityIFDPointer",
        0xA420: "ImageUniqueID"// Identifier assigned uniquely to each image
    };

    var TiffTags = EXIF.TiffTags = {
        0x0100: "ImageWidth",
        0x0101: "ImageHeight",
        0x8769: "ExifIFDPointer",
        0x8825: "GPSInfoIFDPointer",
        0xA005: "InteroperabilityIFDPointer",
        0x0102: "BitsPerSample",
        0x0103: "Compression",
        0x0106: "PhotometricInterpretation",
        0x0112: "Orientation",
        0x0115: "SamplesPerPixel",
        0x011C: "PlanarConfiguration",
        0x0212: "YCbCrSubSampling",
        0x0213: "YCbCrPositioning",
        0x011A: "XResolution",
        0x011B: "YResolution",
        0x0128: "ResolutionUnit",
        0x0111: "StripOffsets",
        0x0116: "RowsPerStrip",
        0x0117: "StripByteCounts",
        0x0201: "JPEGInterchangeFormat",
        0x0202: "JPEGInterchangeFormatLength",
        0x012D: "TransferFunction",
        0x013E: "WhitePoint",
        0x013F: "PrimaryChromaticities",
        0x0211: "YCbCrCoefficients",
        0x0214: "ReferenceBlackWhite",
        0x0132: "DateTime",
        0x010E: "ImageDescription",
        0x010F: "Make",
        0x0110: "Model",
        0x0131: "Software",
        0x013B: "Artist",
        0x8298: "Copyright"
    };

    var GPSTags = EXIF.GPSTags = {
        0x0000: "GPSVersionID",
        0x0001: "GPSLatitudeRef",
        0x0002: "GPSLatitude",
        0x0003: "GPSLongitudeRef",
        0x0004: "GPSLongitude",
        0x0005: "GPSAltitudeRef",
        0x0006: "GPSAltitude",
        0x0007: "GPSTimeStamp",
        0x0008: "GPSSatellites",
        0x0009: "GPSStatus",
        0x000A: "GPSMeasureMode",
        0x000B: "GPSDOP",
        0x000C: "GPSSpeedRef",
        0x000D: "GPSSpeed",
        0x000E: "GPSTrackRef",
        0x000F: "GPSTrack",
        0x0010: "GPSImgDirectionRef",
        0x0011: "GPSImgDirection",
        0x0012: "GPSMapDatum",
        0x0013: "GPSDestLatitudeRef",
        0x0014: "GPSDestLatitude",
        0x0015: "GPSDestLongitudeRef",
        0x0016: "GPSDestLongitude",
        0x0017: "GPSDestBearingRef",
        0x0018: "GPSDestBearing",
        0x0019: "GPSDestDistanceRef",
        0x001A: "GPSDestDistance",
        0x001B: "GPSProcessingMethod",
        0x001C: "GPSAreaInformation",
        0x001D: "GPSDateStamp",
        0x001E: "GPSDifferential"
    };

    // EXIF 2.3 Spec
    var IFD1Tags = EXIF.IFD1Tags = {
        0x0100: "ImageWidth",
        0x0101: "ImageHeight",
        0x0102: "BitsPerSample",
        0x0103: "Compression",
        0x0106: "PhotometricInterpretation",
        0x0111: "StripOffsets",
        0x0112: "Orientation",
        0x0115: "SamplesPerPixel",
        0x0116: "RowsPerStrip",
        0x0117: "StripByteCounts",
        0x011A: "XResolution",
        0x011B: "YResolution",
        0x011C: "PlanarConfiguration",
        0x0128: "ResolutionUnit",
        0x0201: "JpegIFOffset",
        // When image format is JPEG, this value show offset to JPEG data stored.(aka "ThumbnailOffset" or "JPEGInterchangeFormat")
        0x0202: "JpegIFByteCount",
        // When image format is JPEG, this value shows data size of JPEG image (aka "ThumbnailLength" or "JPEGInterchangeFormatLength")
        0x0211: "YCbCrCoefficients",
        0x0212: "YCbCrSubSampling",
        0x0213: "YCbCrPositioning",
        0x0214: "ReferenceBlackWhite"
    };

    var StringValues = EXIF.StringValues = {
        ExposureProgram: {
            0: "Not defined",
            1: "Manual",
            2: "Normal program",
            3: "Aperture priority",
            4: "Shutter priority",
            5: "Creative program",
            6: "Action program",
            7: "Portrait mode",
            8: "Landscape mode"
        },
        MeteringMode: {
            0: "Unknown",
            1: "Average",
            2: "CenterWeightedAverage",
            3: "Spot",
            4: "MultiSpot",
            5: "Pattern",
            6: "Partial",
            255: "Other"
        },
        LightSource: {
            0: "Unknown",
            1: "Daylight",
            2: "Fluorescent",
            3: "Tungsten (incandescent light)",
            4: "Flash",
            9: "Fine weather",
            10: "Cloudy weather",
            11: "Shade",
            12: "Daylight fluorescent (D 5700 - 7100K)",
            13: "Day white fluorescent (N 4600 - 5400K)",
            14: "Cool white fluorescent (W 3900 - 4500K)",
            15: "White fluorescent (WW 3200 - 3700K)",
            17: "Standard light A",
            18: "Standard light B",
            19: "Standard light C",
            20: "D55",
            21: "D65",
            22: "D75",
            23: "D50",
            24: "ISO studio tungsten",
            255: "Other"
        },
        Flash: {
            0x0000: "Flash did not fire",
            0x0001: "Flash fired",
            0x0005: "Strobe return light not detected",
            0x0007: "Strobe return light detected",
            0x0009: "Flash fired, compulsory flash mode",
            0x000D: "Flash fired, compulsory flash mode, return light not detected",
            0x000F: "Flash fired, compulsory flash mode, return light detected",
            0x0010: "Flash did not fire, compulsory flash mode",
            0x0018: "Flash did not fire, auto mode",
            0x0019: "Flash fired, auto mode",
            0x001D: "Flash fired, auto mode, return light not detected",
            0x001F: "Flash fired, auto mode, return light detected",
            0x0020: "No flash function",
            0x0041: "Flash fired, red-eye reduction mode",
            0x0045: "Flash fired, red-eye reduction mode, return light not detected",
            0x0047: "Flash fired, red-eye reduction mode, return light detected",
            0x0049: "Flash fired, compulsory flash mode, red-eye reduction mode",
            0x004D: "Flash fired, compulsory flash mode, red-eye reduction mode, return light not detected",
            0x004F: "Flash fired, compulsory flash mode, red-eye reduction mode, return light detected",
            0x0059: "Flash fired, auto mode, red-eye reduction mode",
            0x005D: "Flash fired, auto mode, return light not detected, red-eye reduction mode",
            0x005F: "Flash fired, auto mode, return light detected, red-eye reduction mode"
        },
        SensingMethod: {
            1: "Not defined",
            2: "One-chip color area sensor",
            3: "Two-chip color area sensor",
            4: "Three-chip color area sensor",
            5: "Color sequential area sensor",
            7: "Trilinear sensor",
            8: "Color sequential linear sensor"
        },
        SceneCaptureType: {
            0: "Standard",
            1: "Landscape",
            2: "Portrait",
            3: "Night scene"
        },
        SceneType: {
            1: "Directly photographed"
        },
        CustomRendered: {
            0: "Normal process",
            1: "Custom process"
        },
        WhiteBalance: {
            0: "Auto white balance",
            1: "Manual white balance"
        },
        GainControl: {
            0: "None",
            1: "Low gain up",
            2: "High gain up",
            3: "Low gain down",
            4: "High gain down"
        },
        Contrast: {
            0: "Normal",
            1: "Soft",
            2: "Hard"
        },
        Saturation: {
            0: "Normal",
            1: "Low saturation",
            2: "High saturation"
        },
        Sharpness: {
            0: "Normal",
            1: "Soft",
            2: "Hard"
        },
        SubjectDistanceRange: {
            0: "Unknown",
            1: "Macro",
            2: "Close view",
            3: "Distant view"
        },
        FileSource: {
            3: "DSC"
        },

        Components: {
            0: "",
            1: "Y",
            2: "Cb",
            3: "Cr",
            4: "R",
            5: "G",
            6: "B"
        }
    };

    function addEvent(element, event, handler) {
        if (element.addEventListener) {
            element.addEventListener(event, handler, false);
        } else if (element.attachEvent) {
            element.attachEvent("on" + event, handler);
        }
    }

    function imageHasData(img) {
        return !!(img.exifdata);
    }

    function base64ToArrayBuffer(base64, contentType) {
        contentType = contentType || base64.match(/^data\:([^\;]+)\;base64,/mi)[1] || '';
        // e.g. 'data:image/jpeg;base64,...' => 'image/jpeg'
        base64 = base64.replace(/^data\:([^\;]+)\;base64,/gmi, '');
        var binary = atob(base64);
        var len = binary.length;
        var buffer = new ArrayBuffer(len);
        var view = new Uint8Array(buffer);
        for (var i = 0; i < len; i++) {
            view[i] = binary.charCodeAt(i);
        }
        return buffer;
    }

    function objectURLToBlob(url, callback) {
        var http = new XMLHttpRequest();
        http.open("GET", url, true);
        http.responseType = "blob";
        http.onload = function(e) {
            if (this.status == 200 || this.status === 0) {
                callback(this.response);
            }
        }
        ;
        http.send();
    }

    function getImageData(img, callback) {
        function handleBinaryFile(binFile) {
            var data = findEXIFinJPEG(binFile);
            img.exifdata = data || {};
            var iptcdata = findIPTCinJPEG(binFile);
            img.iptcdata = iptcdata || {};
            if (EXIF.isXmpEnabled) {
                var xmpdata = findXMPinJPEG(binFile);
                img.xmpdata = xmpdata || {};
            }
            if (callback) {
                callback.call(img);
            }
        }

        if (img.src) {
            if (/^data\:/i.test(img.src)) {
                // Data URI
                var arrayBuffer = base64ToArrayBuffer(img.src);
                handleBinaryFile(arrayBuffer);

            } else if (/^blob\:/i.test(img.src)) {
                // Object URL
                var fileReader = new FileReader();
                fileReader.onload = function(e) {
                    handleBinaryFile(e.target.result);
                }
                ;
                objectURLToBlob(img.src, function(blob) {
                    fileReader.readAsArrayBuffer(blob);
                });
            } else {
                var http = new XMLHttpRequest();
                http.onload = function() {
                    if (this.status == 200 || this.status === 0) {
                        handleBinaryFile(http.response);
                    } else {
                        throw "Could not load image";
                    }
                    http = null;
                }
                ;
                http.open("GET", img.src, true);
                http.responseType = "arraybuffer";
                http.send(null);
            }
        } else if (self.FileReader && (img instanceof self.Blob || img instanceof self.File)) {
            var fileReaderT = new FileReader();
            fileReaderT.onload = function(e) {
                if (debug)
                    console.log("Got file of length " + e.target.result.byteLength);
                handleBinaryFile(e.target.result);
            }
            ;

            fileReaderT.readAsArrayBuffer(img);
        }
    }

    function findEXIFinJPEG(file) {
        var dataView = new DataView(file);

        if (debug)
            console.log("Got file of length " + file.byteLength);
        if ((dataView.getUint8(0) != 0xFF) || (dataView.getUint8(1) != 0xD8)) {
            if (debug)
                console.log("Not a valid JPEG");
            return false;
            // not a valid jpeg
        }

        var offset = 2, length = file.byteLength, marker;

        while (offset < length) {
            if (dataView.getUint8(offset) != 0xFF) {
                if (debug)
                    console.log("Not a valid marker at offset " + offset + ", found: " + dataView.getUint8(offset));
                return false;
                // not a valid marker, something is wrong
            }

            marker = dataView.getUint8(offset + 1);
            if (debug)
                console.log(marker);

            // we could implement handling for other markers here,
            // but we're only looking for 0xFFE1 for EXIF data

            if (marker == 225) {
                if (debug)
                    console.log("Found 0xFFE1 marker");

                return readEXIFData(dataView, offset + 4, dataView.getUint16(offset + 2) - 2);

                // offset += 2 + file.getShortAt(offset+2, true);

            } else {
                offset += 2 + dataView.getUint16(offset + 2);
            }

        }

    }

    function findIPTCinJPEG(file) {
        var dataView = new DataView(file);

        if (debug)
            console.log("Got file of length " + file.byteLength);
        if ((dataView.getUint8(0) != 0xFF) || (dataView.getUint8(1) != 0xD8)) {
            if (debug)
                console.log("Not a valid JPEG");
            return false;
            // not a valid jpeg
        }

        var offset = 2, length = file.byteLength;

        var isFieldSegmentStart = function(dataView, offset) {
            return (dataView.getUint8(offset) === 0x38 && dataView.getUint8(offset + 1) === 0x42 && dataView.getUint8(offset + 2) === 0x49 && dataView.getUint8(offset + 3) === 0x4D && dataView.getUint8(offset + 4) === 0x04 && dataView.getUint8(offset + 5) === 0x04);
        };

        while (offset < length) {

            if (isFieldSegmentStart(dataView, offset)) {

                // Get the length of the name header (which is padded to an even number of bytes)
                var nameHeaderLength = dataView.getUint8(offset + 7);
                if (nameHeaderLength % 2 !== 0)
                    nameHeaderLength += 1;
                // Check for pre photoshop 6 format
                if (nameHeaderLength === 0) {
                    // Always 4
                    nameHeaderLength = 4;
                }

                var startOffset = offset + 8 + nameHeaderLength;
                var sectionLength = dataView.getUint16(offset + 6 + nameHeaderLength);

                return readIPTCData(file, startOffset, sectionLength);

            }

            // Not the marker, continue searching
            offset++;

        }

    }
    var IptcFieldMap = {
        0x78: 'caption',
        0x6E: 'credit',
        0x19: 'keywords',
        0x37: 'dateCreated',
        0x50: 'byline',
        0x55: 'bylineTitle',
        0x7A: 'captionWriter',
        0x69: 'headline',
        0x74: 'copyright',
        0x0F: 'category'
    };

    function readIPTCData(file, startOffset, sectionLength) {
        var dataView = new DataView(file);
        var data = {};
        var fieldValue, fieldName, dataSize, segmentType, segmentSize;
        var segmentStartPos = startOffset;
        while (segmentStartPos < startOffset + sectionLength) {
            if (dataView.getUint8(segmentStartPos) === 0x1C && dataView.getUint8(segmentStartPos + 1) === 0x02) {
                segmentType = dataView.getUint8(segmentStartPos + 2);
                if (segmentType in IptcFieldMap) {
                    dataSize = dataView.getInt16(segmentStartPos + 3);
                    segmentSize = dataSize + 5;
                    fieldName = IptcFieldMap[segmentType];
                    fieldValue = getStringFromDB(dataView, segmentStartPos + 5, dataSize);
                    // Check if we already stored a value with this name
                    if (data.hasOwnProperty(fieldName)) {
                        // Value already stored with this name, create multivalue field
                        if (data[fieldName]instanceof Array) {
                            data[fieldName].push(fieldValue);
                        } else {
                            data[fieldName] = [data[fieldName], fieldValue];
                        }
                    } else {
                        data[fieldName] = fieldValue;
                    }
                }

            }
            segmentStartPos++;
        }
        return data;
    }

    function readTags(file, tiffStart, dirStart, strings, bigEnd) {
        var entries = file.getUint16(dirStart, !bigEnd), tags = {}, entryOffset, tag, i;

        for (i = 0; i < entries; i++) {
            entryOffset = dirStart + i * 12 + 2;
            tag = strings[file.getUint16(entryOffset, !bigEnd)];
            if (!tag && debug)
                console.log("Unknown tag: " + file.getUint16(entryOffset, !bigEnd));
            tags[tag] = readTagValue(file, entryOffset, tiffStart, dirStart, bigEnd);
        }
        return tags;
    }

    function readTagValue(file, entryOffset, tiffStart, dirStart, bigEnd) {
        var type = file.getUint16(entryOffset + 2, !bigEnd), numValues = file.getUint32(entryOffset + 4, !bigEnd), valueOffset = file.getUint32(entryOffset + 8, !bigEnd) + tiffStart, offset, vals, val, n, numerator, denominator;

        switch (type) {
        case 1:
            // byte, 8-bit unsigned int
        case 7:
            // undefined, 8-bit byte, value depending on field
            if (numValues == 1) {
                return file.getUint8(entryOffset + 8, !bigEnd);
            } else {
                offset = numValues > 4 ? valueOffset : (entryOffset + 8);
                vals = [];
                for (n = 0; n < numValues; n++) {
                    vals[n] = file.getUint8(offset + n);
                }
                return vals;
            }
            break;
        case 2:
            // ascii, 8-bit byte
            offset = numValues > 4 ? valueOffset : (entryOffset + 8);
            return getStringFromDB(file, offset, numValues - 1);

        case 3:
            // short, 16 bit int
            if (numValues == 1) {
                return file.getUint16(entryOffset + 8, !bigEnd);
            } else {
                offset = numValues > 2 ? valueOffset : (entryOffset + 8);
                vals = [];
                for (n = 0; n < numValues; n++) {
                    vals[n] = file.getUint16(offset + 2 * n, !bigEnd);
                }
                return vals;
            }
            break;
        case 4:
            // long, 32 bit int
            if (numValues == 1) {
                return file.getUint32(entryOffset + 8, !bigEnd);
            } else {
                vals = [];
                for (n = 0; n < numValues; n++) {
                    vals[n] = file.getUint32(valueOffset + 4 * n, !bigEnd);
                }
                return vals;
            }
            break;
        case 5:
            // rational = two long values, first is numerator, second is denominator
            if (numValues == 1) {
                numerator = file.getUint32(valueOffset, !bigEnd);
                denominator = file.getUint32(valueOffset + 4, !bigEnd);
                val = new Number(numerator / denominator);
                val.numerator = numerator;
                val.denominator = denominator;
                return val;
            } else {
                vals = [];
                for (n = 0; n < numValues; n++) {
                    numerator = file.getUint32(valueOffset + 8 * n, !bigEnd);
                    denominator = file.getUint32(valueOffset + 4 + 8 * n, !bigEnd);
                    vals[n] = new Number(numerator / denominator);
                    vals[n].numerator = numerator;
                    vals[n].denominator = denominator;
                }
                return vals;
            }

        case 9:
            // slong, 32 bit signed int
            if (numValues == 1) {
                return file.getInt32(entryOffset + 8, !bigEnd);
            } else {
                vals = [];
                for (n = 0; n < numValues; n++) {
                    vals[n] = file.getInt32(valueOffset + 4 * n, !bigEnd);
                }
                return vals;
            }

        case 10:
            // signed rational, two slongs, first is numerator, second is denominator
            if (numValues == 1) {
                return file.getInt32(valueOffset, !bigEnd) / file.getInt32(valueOffset + 4, !bigEnd);
            } else {
                vals = [];
                for (n = 0; n < numValues; n++) {
                    vals[n] = file.getInt32(valueOffset + 8 * n, !bigEnd) / file.getInt32(valueOffset + 4 + 8 * n, !bigEnd);
                }
                return vals;
            }
        }
    }

    /**
			* Given an IFD (Image File Directory) start offset
			* returns an offset to next IFD or 0 if it's the last IFD.
			*/
    function getNextIFDOffset(dataView, dirStart, bigEnd) {
        //the first 2bytes means the number of directory entries contains in this IFD
        var entries = dataView.getUint16(dirStart, !bigEnd);

        // After last directory entry, there is a 4bytes of data,
        // it means an offset to next IFD.
        // If its value is '0x00000000', it means this is the last IFD and there is no linked IFD.

        return dataView.getUint32(dirStart + 2 + entries * 12, !bigEnd);
        // each entry is 12 bytes long
    }

    function readThumbnailImage(dataView, tiffStart, firstIFDOffset, bigEnd) {
        // get the IFD1 offset
        var IFD1OffsetPointer = getNextIFDOffset(dataView, tiffStart + firstIFDOffset, bigEnd);

        if (!IFD1OffsetPointer) {
            // console.log('******** IFD1Offset is empty, image thumb not found ********');
            return {};
        } else if (IFD1OffsetPointer > dataView.byteLength) {
            // this should not happen
            // console.log('******** IFD1Offset is outside the bounds of the DataView ********');
            return {};
        }
        // console.log('*******  thumbnail IFD offset (IFD1) is: %s', IFD1OffsetPointer);

        var thumbTags = readTags(dataView, tiffStart, tiffStart + IFD1OffsetPointer, IFD1Tags, bigEnd)

        // EXIF 2.3 specification for JPEG format thumbnail

        // If the value of Compression(0x0103) Tag in IFD1 is '6', thumbnail image format is JPEG.
        // Most of Exif image uses JPEG format for thumbnail. In that case, you can get offset of thumbnail
        // by JpegIFOffset(0x0201) Tag in IFD1, size of thumbnail by JpegIFByteCount(0x0202) Tag.
        // Data format is ordinary JPEG format, starts from 0xFFD8 and ends by 0xFFD9. It seems that
        // JPEG format and 160x120pixels of size are recommended thumbnail format for Exif2.1 or later.

        if (thumbTags['Compression']) {
            // console.log('Thumbnail image found!');

            switch (thumbTags['Compression']) {
            case 6:
                // console.log('Thumbnail image format is JPEG');
                if (thumbTags.JpegIFOffset && thumbTags.JpegIFByteCount) {
                    // extract the thumbnail
                    var tOffset = tiffStart + thumbTags.JpegIFOffset;
                    var tLength = thumbTags.JpegIFByteCount;
                    thumbTags['blob'] = new Blob([new Uint8Array(dataView.buffer,tOffset,tLength)],{
                        type: 'image/jpeg'
                    });
                }
                break;

            case 1:
                console.log("Thumbnail image format is TIFF, which is not implemented.");
                break;
            default:
                console.log("Unknown thumbnail image format '%s'", thumbTags['Compression']);
            }
        } else if (thumbTags['PhotometricInterpretation'] == 2) {
            console.log("Thumbnail image format is RGB, which is not implemented.");
        }
        return thumbTags;
    }

    function getStringFromDB(buffer, start, length) {
        var outstr = "";
        for (var n = start; n < start + length; n++) {
            outstr += String.fromCharCode(buffer.getUint8(n));
        }
        return outstr;
    }

    function readEXIFData(file, start) {
        if (getStringFromDB(file, start, 4) != "Exif") {
            if (debug)
                console.log("Not valid EXIF data! " + getStringFromDB(file, start, 4));
            return false;
        }

        var bigEnd, tags, tag, exifData, gpsData, tiffOffset = start + 6;

        // test for TIFF validity and endianness
        if (file.getUint16(tiffOffset) == 0x4949) {
            bigEnd = false;
        } else if (file.getUint16(tiffOffset) == 0x4D4D) {
            bigEnd = true;
        } else {
            if (debug)
                console.log("Not valid TIFF data! (no 0x4949 or 0x4D4D)");
            return false;
        }

        if (file.getUint16(tiffOffset + 2, !bigEnd) != 0x002A) {
            if (debug)
                console.log("Not valid TIFF data! (no 0x002A)");
            return false;
        }

        var firstIFDOffset = file.getUint32(tiffOffset + 4, !bigEnd);

        if (firstIFDOffset < 0x00000008) {
            if (debug)
                console.log("Not valid TIFF data! (First offset less than 8)", file.getUint32(tiffOffset + 4, !bigEnd));
            return false;
        }

        tags = readTags(file, tiffOffset, tiffOffset + firstIFDOffset, TiffTags, bigEnd);

        if (tags.ExifIFDPointer) {
            exifData = readTags(file, tiffOffset, tiffOffset + tags.ExifIFDPointer, ExifTags, bigEnd);
            for (tag in exifData) {
                switch (tag) {
                case "LightSource":
                case "Flash":
                case "MeteringMode":
                case "ExposureProgram":
                case "SensingMethod":
                case "SceneCaptureType":
                case "SceneType":
                case "CustomRendered":
                case "WhiteBalance":
                case "GainControl":
                case "Contrast":
                case "Saturation":
                case "Sharpness":
                case "SubjectDistanceRange":
                case "FileSource":
                    exifData[tag] = StringValues[tag][exifData[tag]];
                    break;

                case "ExifVersion":
                case "FlashpixVersion":
                    exifData[tag] = String.fromCharCode(exifData[tag][0], exifData[tag][1], exifData[tag][2], exifData[tag][3]);
                    break;

                case "ComponentsConfiguration":
                    exifData[tag] = StringValues.Components[exifData[tag][0]] + StringValues.Components[exifData[tag][1]] + StringValues.Components[exifData[tag][2]] + StringValues.Components[exifData[tag][3]];
                    break;
                }
                tags[tag] = exifData[tag];
            }
        }

        if (tags.GPSInfoIFDPointer) {
            gpsData = readTags(file, tiffOffset, tiffOffset + tags.GPSInfoIFDPointer, GPSTags, bigEnd);
            for (tag in gpsData) {
                switch (tag) {
                case "GPSVersionID":
                    gpsData[tag] = gpsData[tag][0] + "." + gpsData[tag][1] + "." + gpsData[tag][2] + "." + gpsData[tag][3];
                    break;
                }
                tags[tag] = gpsData[tag];
            }
        }

        // extract thumbnail
        tags['thumbnail'] = readThumbnailImage(file, tiffOffset, firstIFDOffset, bigEnd);

        return tags;
    }

    function findXMPinJPEG(file) {

        if (!('DOMParser'in self)) {
            // console.warn('XML parsing not supported without DOMParser');
            return;
        }
        var dataView = new DataView(file);

        if (debug)
            console.log("Got file of length " + file.byteLength);
        if ((dataView.getUint8(0) != 0xFF) || (dataView.getUint8(1) != 0xD8)) {
            if (debug)
                console.log("Not a valid JPEG");
            return false;
            // not a valid jpeg
        }

        var offset = 2
          , length = file.byteLength
          , dom = new DOMParser();

        while (offset < (length - 4)) {
            if (getStringFromDB(dataView, offset, 4) == "http") {
                var startOffset = offset - 1;
                var sectionLength = dataView.getUint16(offset - 2) - 1;
                var xmpString = getStringFromDB(dataView, startOffset, sectionLength)
                var xmpEndIndex = xmpString.indexOf('xmpmeta>') + 8;
                xmpString = xmpString.substring(xmpString.indexOf('<x:xmpmeta'), xmpEndIndex);

                var indexOfXmp = xmpString.indexOf('x:xmpmeta') + 10
                //Many custom written programs embed xmp/xml without any namespace. Following are some of them.
                //Without these namespaces, XML is thought to be invalid by parsers
                xmpString = xmpString.slice(0, indexOfXmp) + 'xmlns:Iptc4xmpCore="http://iptc.org/std/Iptc4xmpCore/1.0/xmlns/" ' + 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ' + 'xmlns:tiff="http://ns.adobe.com/tiff/1.0/" ' + 'xmlns:plus="http://schemas.android.com/apk/lib/com.google.android.gms.plus" ' + 'xmlns:ext="http://www.gettyimages.com/xsltExtension/1.0" ' + 'xmlns:exif="http://ns.adobe.com/exif/1.0/" ' + 'xmlns:stEvt="http://ns.adobe.com/xap/1.0/sType/ResourceEvent#" ' + 'xmlns:stRef="http://ns.adobe.com/xap/1.0/sType/ResourceRef#" ' + 'xmlns:crs="http://ns.adobe.com/camera-raw-settings/1.0/" ' + 'xmlns:xapGImg="http://ns.adobe.com/xap/1.0/g/img/" ' + 'xmlns:Iptc4xmpExt="http://iptc.org/std/Iptc4xmpExt/2008-02-29/" ' + xmpString.slice(indexOfXmp)

                var domDocument = dom.parseFromString(xmpString, 'text/xml');
                return xml2Object(domDocument);
            } else {
                offset++;
            }
        }
    }

    function xml2json(xml) {
        var json = {};

        if (xml.nodeType == 1) {
            // element node
            if (xml.attributes.length > 0) {
                json['@attributes'] = {};
                for (var j = 0; j < xml.attributes.length; j++) {
                    var attribute = xml.attributes.item(j);
                    json['@attributes'][attribute.nodeName] = attribute.nodeValue;
                }
            }
        } else if (xml.nodeType == 3) {
            // text node
            return xml.nodeValue;
        }

        // deal with children
        if (xml.hasChildNodes()) {
            for (var i = 0; i < xml.childNodes.length; i++) {
                var child = xml.childNodes.item(i);
                var nodeName = child.nodeName;
                if (json[nodeName] == null) {
                    json[nodeName] = xml2json(child);
                } else {
                    if (json[nodeName].push == null) {
                        var old = json[nodeName];
                        json[nodeName] = [];
                        json[nodeName].push(old);
                    }
                    json[nodeName].push(xml2json(child));
                }
            }
        }

        return json;
    }

    function xml2Object(xml) {
        try {
            var obj = {};
            if (xml.children.length > 0) {
                for (var i = 0; i < xml.children.length; i++) {
                    var item = xml.children.item(i);
                    var attributes = item.attributes;
                    for (var idx in attributes) {
                        var itemAtt = attributes[idx];
                        var dataKey = itemAtt.nodeName;
                        var dataValue = itemAtt.nodeValue;

                        if (dataKey !== undefined) {
                            obj[dataKey] = dataValue;
                        }
                    }
                    var nodeName = item.nodeName;

                    if (typeof (obj[nodeName]) == "undefined") {
                        obj[nodeName] = xml2json(item);
                    } else {
                        if (typeof (obj[nodeName].push) == "undefined") {
                            var old = obj[nodeName];

                            obj[nodeName] = [];
                            obj[nodeName].push(old);
                        }
                        obj[nodeName].push(xml2json(item));
                    }
                }
            } else {
                obj = xml.textContent;
            }
            return obj;
        } catch (e) {
            console.log(e.message);
        }
    }

    EXIF.enableXmp = function() {
        EXIF.isXmpEnabled = true;
    }

    EXIF.disableXmp = function() {
        EXIF.isXmpEnabled = false;
    }

    EXIF.getData = function(img, callback) {
        if (((self.Image && img instanceof self.Image) || (self.HTMLImageElement && img instanceof self.HTMLImageElement)) && !img.complete)
            return false;

        if (!imageHasData(img)) {
            getImageData(img, callback);
        } else {
            if (callback) {
                callback.call(img);
            }
        }
        return true;
    }

    EXIF.getTag = function(img, tag) {
        if (!imageHasData(img))
            return;
        return img.exifdata[tag];
    }

    EXIF.getIptcTag = function(img, tag) {
        if (!imageHasData(img))
            return;
        return img.iptcdata[tag];
    }

    EXIF.getAllTags = function(img) {
        if (!imageHasData(img))
            return {};
        var a, data = img.exifdata, tags = {};
        for (a in data) {
            if (data.hasOwnProperty(a)) {
                tags[a] = data[a];
            }
        }
        return tags;
    }

    EXIF.getAllIptcTags = function(img) {
        if (!imageHasData(img))
            return {};
        var a, data = img.iptcdata, tags = {};
        for (a in data) {
            if (data.hasOwnProperty(a)) {
                tags[a] = data[a];
            }
        }
        return tags;
    }

    EXIF.pretty = function(img) {
        if (!imageHasData(img))
            return "";
        var a, data = img.exifdata, strPretty = "";
        for (a in data) {
            if (data.hasOwnProperty(a)) {
                if (typeof data[a] == "object") {
                    if (data[a]instanceof Number) {
                        strPretty += a + " : " + data[a] + " [" + data[a].numerator + "/" + data[a].denominator + "]\r\n";
                    } else {
                        strPretty += a + " : [" + data[a].length + " values]\r\n";
                    }
                } else {
                    strPretty += a + " : " + data[a] + "\r\n";
                }
            }
        }
        return strPretty;
    }

    EXIF.readFromBinaryFile = function(file) {
        return findEXIFinJPEG(file);
    }

    if (typeof define === 'function' && define.amd) {
        define('exif-js', [], function() {
            return EXIF;
        });
    }
}
.call(this));

const imageQuality = document.querySelector("body").classList.contains("fimgqlty") ? 0.70 : 0.70;
var CustomPhotoProcessor = {
    sx: 0,
    sy: 0,
    crop_image_dimension: {
        width: 0,
        height: 0,
        focusAreaWidth: 0,
        focusAreaHeight: 0,
        focusAreaWidth_parent: 0,
        focusAreaHeight_parent: 0,
    },
    __init__: async function() {
        var spmbtn = document.getElementsByClassName("user-profile-picture-upload-btn")
          , spmbtn_len = spmbtn.length;

        for (var up = 0; up < spmbtn_len; up++) {
            spmbtn[up].addEventListener("click", function() {
                if (document.getElementById("photo_upload_modal") !== "undefined" || document.getElementById("photo_upload_modal") !== null) {
                    window.scrollTo(0, 0);
                    CustomPhotoProcessor.changeRoute("pumc-cc-1");
                    document.getElementById("photo_upload_modal").style.display = "block";
                }
                if (document.getElementById("pumc_crop_image") !== "undefined" || document.getElementById("pumc_crop_image") !== null) {
                    document.getElementById("pumc_crop_image").removeAttribute("style");
                    CustomPhotoProcessor.sx = 0;
                    CustomPhotoProcessor.sy = 0;
                    CustomPhotoProcessor.crop_image_dimension = {
                        width: 0,
                        height: 0,
                        focusAreaWidth: 0,
                        focusAreaHeight: 0,
                        focusAreaWidth_parent: 0,
                        focusAreaHeight_parent: 0,
                    };
                    CustomPhotoProcessor.attachMovesToCropImage();
					CustomPhotoProcessor.restoreCropImageEventListener();
                }
            }, false);
        }

        CustomPhotoProcessor.__PLATFORM__ = await CustomPhotoProcessor.__PLATFORM();

        if (document.getElementById("pumc_closer_btn") !== "undefined" && document.getElementById("pumc_closer_btn") !== null) {
            document.getElementById("pumc_closer_btn").addEventListener("click", function() {
                if (document.getElementById("photo_upload_modal") !== "undefined" && document.getElementById("photo_upload_modal") !== null) {
                    document.getElementById("photo_upload_modal").style.display = "none";
                }
            }, false);
        }
        

        if (document.getElementById("crop_normal_upload") !== "undefined" && document.getElementById("crop_normal_upload") !== null) {
            document.getElementById("crop_normal_upload").addEventListener("click", function() {
                CustomPhotoProcessor.changeRoute("pumc-cc-3");
            }, false);
        }

        if (document.getElementById("cancel_normal_upload") !== "undefined" && document.getElementById("cancel_normal_upload") !== null) {
            document.getElementById("cancel_normal_upload").addEventListener("click", function() {
                CustomPhotoProcessor.changeRoute("pumc-cc-1");
				CustomPhotoProcessor.restoreCropImageEventListener();
            }, false);
        }
		
		if (document.getElementById("increase_crop_image_size") !== "undefined" && document.getElementById("increase_crop_image_size") !== null) {
            document.getElementById("increase_crop_image_size").addEventListener("click", function() {
                CustomPhotoProcessor.controlCropImageDimension("inc");
            }, false);
        }

        if (document.getElementById("reduce_crop_image_size") !== "undefined" && document.getElementById("reduce_crop_image_size") !== null) {
            document.getElementById("reduce_crop_image_size").addEventListener("click", function() {
                CustomPhotoProcessor.controlCropImageDimension("dec");
            }, false);
        }

        if (document.getElementById("default_normal_cropping") !== "undefined" && document.getElementById("default_normal_cropping") !== null) {
            document.getElementById("default_normal_cropping").addEventListener("click", function() {
                var d = document.getElementById("pumc_crop_image");
                d.removeAttribute("style");
                d.src = document.getElementById("default_normal_cropping").getAttribute("data-src");
            }, false);
        }

        if (document.getElementById("cancel_normal_cropping") !== "undefined" && document.getElementById("cancel_normal_cropping") !== null) {
            document.getElementById("cancel_normal_cropping").addEventListener("click", function() {
                CustomPhotoProcessor.changeRoute("pumc-cc-1");
				CustomPhotoProcessor.restoreCropImageEventListener();
            }, false);
        }

        CustomPhotoProcessor.attachMovesToCropImage();

    },
	closePhotoModal : function(){
		if (document.getElementById("photo_upload_modal") !== "undefined" && document.getElementById("photo_upload_modal") !== null) {
            document.getElementById("photo_upload_modal").style.display = "none";
        }
	},
	controlCropImageDimension : function(state){
		if(document.getElementById("pumc_crop_image") === "undefined" && document.getElementById("pumc_crop_image") === null){
			return;
		}
		switch(state){
			case "inc":
				var img = new Image();
				img.src = document.getElementById("default_normal_cropping").getAttribute("data-src");
                img.onload = function(){
					var d = document.getElementById("pumc_crop_image");
					var canvas = document.createElement("CANVAS"),
						ctx = canvas.getContext("2d", { willReadFrequently: true }),
						ar = CustomPhotoProcessor.calculateAspectRatioFit(d.width + 20, d.height, d.width + 20, d.height);
						d.width = d.width + 50;
                        canvas.width = d.width;
						canvas.height = d.height;
						ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
						d.src = canvas.toDataURL("image/jpeg", 1.0);
						CustomPhotoProcessor.restoreCropImageEventListener();	
				};
				break;
			default:
				var img = new Image();
				img.src = document.getElementById("default_normal_cropping").getAttribute("data-src");
				img.onload = function(){
					var d = document.getElementById("pumc_crop_image");
					var canvas = document.createElement("CANVAS"),
						ctx = canvas.getContext("2d", { willReadFrequently: true }),
						ar = CustomPhotoProcessor.calculateAspectRatioFitTwo(d.width - 20, d.height, d.width - 20, d.height);
						d.width = d.width - 50;
                        canvas.width = d.width;
						canvas.height = d.height;
						ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
						d.src = canvas.toDataURL("image/jpeg", imageQuality);
						CustomPhotoProcessor.restoreCropImageEventListener();	
				};
				break;
		}
	},
	restoreCropImageEventListener : function(){
		CustomPhotoProcessor.sx = 0;
		CustomPhotoProcessor.sy = 0;
		CustomPhotoProcessor.attachMovesToCropImage();
		// document.getElementById("pumc_crop_image").removeAttribute("style");
	},
    calculateAspectRatioFit: function(srcWidth, srcHeight, maxWidth, maxHeight) {
        var ratio = Math.min(maxWidth / srcWidth, maxHeight / srcHeight);
        return {
            width: srcWidth * ratio,
            height: srcHeight * ratio
        };
    },
	calculateAspectRatioFitTwo : function(width, height, MAX_WIDTH, MAX_HEIGHT){
		if (width > height) {
            if (width > MAX_WIDTH) {
                height *= MAX_WIDTH / width;
                width = MAX_WIDTH;
            }
        } else {
            if (height > MAX_HEIGHT) {
                width *= MAX_HEIGHT / height;
                height = MAX_HEIGHT;
            }
        }
		
		return {
			width : width,
			height: height,
		}
	},
    handlePhotoSelectDirect: function(file, callBack, options) {

        if (typeof file !== "object") {
            callBack({
                statusText: "No file object found. Please select a file before you proceed.",
                status: '_FAILED',
            });
            throw new Error("No file object found. Please select a file before you proceed.");
            return;
        }

        if (typeof callBack !== "function") {
            callBack({
                statusText: "Incomplete number of arguments(3 needed). No callback function is declared for this call.",
                status: '_FAILED',
            });
            throw new Error("Incomplete number of arguments(3 needed). No callback function is declared for this call.");
            return;
        }

        if (typeof options !== "object") {
            var options = {
                minWidth: 300,
                maxWidthAspectRatio: 1200,
                maxHeightAspectRatio: 768,
            };
        }

        if (!('maxWidthAspectRatio'in options)) {
            options.maxWidthAspectRatio = 1200;
        }

        if (!('maxHeightAspectRatio'in options)) {
            options.maxHeightAspectRatio = 768;
        }

        if (!('minWidth'in options)) {
            options.minWidth = 300;
        }

        if (options.minWidth <= 0 || options.maxWidthAspectRatio <= 0) {
            callBack({
                statusText: "There is a problem with this image.",
                status: '_FAILED',
            });
            throw new Error("There is a problem with this image.");
            return;
        }

        var F, L;
        F = file;
        L = F.length;

        Array.prototype.slice.call(F).forEach(function(f, ind) {
            var reader = new FileReader();
            reader.onload = function(e) {
                switch (f.type.toLowerCase()) {
                case "image/jpeg":
                case "image/jpeg":
                case "image/png":
                case "image/svg+xml":
                    let img = new Image();
                    img.onload = function(){
                        callBack({
                            image: e.target.result,
                            dimension: {
                                width: img.width,
                                height: img.height,
                                minWidth: options.minWidth
                            },
                            status: '_OK',
                            statusText: "Image successfully created.",
                        });
                    };
                    img.onerror = function(){
                        callBack({
                            statusText: "An unknown error occurred.",
                            status: '_FAILED',
                        });
                        throw new Error("An unknown error occurred.");
                    };
                    img.src = e.target.result;
                    return;
                    break;
                default:
                    callBack({
                        statusText: "Image type for " + f.name + " is not accepted. Only .jpg, .png, and .jpeg, .svg types are accepted.",
                        status: '_FAILED',
                    });
                    throw new Error("Image type for " + f.name + " is not accepted. Only .jpg, .png, and .jpeg, .svg types are accepted.");
                    return;
                }
            }
            reader.readAsDataURL(f);
        });
    },
    handlePhotoSelect: function(file, callBack, options) {

        if (typeof file !== "object") {
            callBack({
                statusText: "No file object found. Please select a file before you proceed.",
                status: '_FAILED',
            });
            throw new Error("No file object found. Please select a file before you proceed.");
            return;
        }

        if (typeof callBack !== "function") {
            callBack({
                statusText: "Incomplete number of arguments(3 needed). No callback function is declared for this call.",
                status: '_FAILED',
            });
            throw new Error("Incomplete number of arguments(3 needed). No callback function is declared for this call.");
            return;
        }

        if (typeof options !== "object") {
            var options = {
                minWidth: 300,
                maxWidthAspectRatio: 1200,
                maxHeightAspectRatio: 768,
            };
        }

        if (!('maxWidthAspectRatio'in options)) {
            options.maxWidthAspectRatio = 1200;
        }

        if (!('maxHeightAspectRatio'in options)) {
            options.maxHeightAspectRatio = 768;
        }

        if (!('minWidth'in options)) {
            options.minWidth = 300;
        }

        if (options.minWidth <= 0 || options.maxWidthAspectRatio <= 0) {
            callBack({
                statusText: "There is a problem with this image.",
                status: '_FAILED',
            });
            throw new Error("There is a problem with this image.");
            return;
        }

        var F, L;
        F = file;
        L = F.length;

        Array.prototype.slice.call(F).forEach(function(f, ind) {
            var reader = new FileReader();
            reader.onload = function(e) {
                switch (f.type.toLowerCase()) {
                case "image/jpeg":
                case "image/jpeg":
                case "image/png":
                    CustomPhotoProcessor.addPhotos(e.target.result, f.type, callBack, options);
                    break;
                default:
                    callBack({
                        statusText: "Image type for " + f.name + " is not accepted. Only .jpg, .png, and .jpeg types are accepted.",
                        status: '_FAILED',
                    });
                    throw new Error("Image type for " + f.name + " is not accepted. Only .jpg, .png, and .jpeg types are accepted.");
                    return;
                }
            }
            reader.readAsDataURL(f);
        });
    },
    handlePhotoSelectSingle: function(file, callBack, options) {
        if (typeof file !== "object") {
            callBack({
                statusText: "No file object found. Please select a file before you proceed.",
                status: '_FAILED',
            });
            throw new Error("No file object found. Please select a file before you proceed.");
            return;
        }

        if (typeof callBack !== "function") {
            callBack({
                statusText: "Incomplete number of arguments(3 needed). No callback function is declared for this call.",
                status: '_FAILED',
            });
            throw new Error("Incomplete number of arguments(3 needed). No callback function is declared for this call.");
            return;
        }

        if (typeof options !== "object") {
            var options = {
                minWidth: 300,
                maxWidthAspectRatio: 1200,
                maxHeightAspectRatio: 768,
                format: "image/jpeg",
            };
        }

        if (!('maxWidthAspectRatio'in options)) {
            options.maxWidthAspectRatio = 1200;
        }

        if (!('maxHeightAspectRatio'in options)) {
            options.maxHeightAspectRatio = 768;
        }

        if (!('minWidth'in options)) {
            options.minWidth = 300;
        }

        if (!('format'in options)) {
            options.format = "image/jpeg";
        }

        if (options.minWidth <= 0 || options.maxWidthAspectRatio <= 0) {
            callBack({
                statusText: "There is a problem with this image.",
                status: '_FAILED',
            });
            throw new Error("There is a problem with this image.");
            return;
        }

        var reader = new FileReader();
        reader.onload = function(e) {
            switch (file.type.toLowerCase()) {
            case "image/jpeg":
            case "image/jpg":
            case "image/png":
                CustomPhotoProcessor.addPhotos(e.target.result, file.type, callBack, options);
                break;
            default:
                callBack({
                    statusText: "Image type for " + file.name + " is not accepted. Only .jpg, .png, and .jpeg types are accepted.",
                    status: '_FAILED',
                });
                throw new Error("Image type for " + file.name + " is not accepted. Only .jpg, .png, and .jpeg types are accepted.");
                return;
            }
        }
        reader.readAsDataURL(file);
    },
    handlePhotoSelectDataURL: function(progressContainer, file, callBack, options) {

        if (typeof callBack !== "function") {
            callBack({
                statusText: "Incomplete number of arguments(3 needed). No callback function is declared for this call.",
                status: '_FAILED',
            });
            throw new Error("Incomplete number of arguments(3 needed). No callback function is declared for this call.");
            return;
        }

        if (typeof options !== "object") {
            var options = {
                minWidth: 300,
                maxWidthAspectRatio: 1200,
                maxHeightAspectRatio: 768,
                format: "image/jpeg"
            };
        }

        if (!('maxWidthAspectRatio'in options)) {
            options.maxWidthAspectRatio = 1200;
        }

        if (!('maxHeightAspectRatio'in options)) {
            options.maxHeightAspectRatio = 768;
        }

        if (!('minWidth'in options)) {
            options.minWidth = 300;
        }

        if (!('format'in options)) {
            options.format = "image/jpeg";
        }

        if (options.minWidth <= 0 || options.maxWidthAspectRatio <= 0) {
            callBack({
                statusText: "There is a problem with this image.",
                status: '_FAILED',
            });
            throw new Error("There is a problem with this image.");
            return;
        }

        var reader = new FileReader();

        reader.onload = function(e) {
            switch (file.type.toLowerCase()) {
            case "image/jpeg":
            case "image/jpg":
            case "image/png":
                CustomPhotoProcessor.addPhotos(e.target.result, file.type, callBack, options);
                break;
            default:
                callBack({
                    statusText: "Image type for " + file.name + " is not accepted. Only .jpg, .png, and .jpeg types are accepted.",
                    status: '_FAILED',
                });
                throw new Error("Image type for " + file.name + " is not accepted. Only .jpg, .png, and .jpeg types are accepted.");
                return;
            }
        }

        reader.onprogress = function(e){
            progressContainer.style.width = ((e.loaded/e.total) * 100) + "%";
        }

        reader.readAsDataURL(file);
    },
    handlePhotoSelectWithBuffer: function(progressContainer, file, callBack, options) {

        if (typeof callBack !== "function") {
            callBack({
                statusText: "Incomplete number of arguments(3 needed). No callback function is declared for this call.",
                status: '_FAILED',
            });
            throw new Error("Incomplete number of arguments(3 needed). No callback function is declared for this call.");
        }

        if (typeof options !== "object") {
            var options = {
                minWidth: 300,
                maxWidthAspectRatio: 1200,
                maxHeightAspectRatio: 768,
                format: "image/jpeg"
            };
        }

        if (!('maxWidthAspectRatio'in options)) {
            options.maxWidthAspectRatio = 1200;
        }

        if (!('maxHeightAspectRatio'in options)) {
            options.maxHeightAspectRatio = 768;
        }

        if (!('minWidth'in options)) {
            options.minWidth = 300;
        }

        if (!('format'in options)) {
            options.format = "image/jpeg";
        }

        var reader = new FileReader();

        reader.onload = function(e) {
            switch (file.type.toLowerCase()) {
            case "image/jpeg":
            case "image/jpg":
            case "image/png":
                let photo;
                photo = new Blob([new Uint8Array(e.target.result)], { type: file.type, name: 'simple' });
                photo.lastModifiedDate = "imgitm-" + new Date();
                photo.name = file.name;
                CustomPhotoProcessor.addPhotos(window.URL.createObjectURL(photo), file.type, callBack, options);
                break;
            default:
                callBack({
                    statusText: "Image type for " + file.name + " is not accepted. Only .jpg, .png, and .jpeg types are accepted.",
                    status: '_FAILED',
                });
                throw new Error("Image type for " + file.name + " is not accepted. Only .jpg, .png, and .jpeg types are accepted.");
            }
        }

        reader.onprogress = function(e){
            progressContainer.style.width = ((e.loaded/e.total) * 100) + "%";
        }
        
        reader.readAsArrayBuffer(file);
    },
    addPhotos: function(data, type, callBack, options) {
        var img = new Image(), nimg = "", canvas, ctx, aspc, orientation;
        img.cors = "anonymous";
        img.crossOrigin = "anonymous";
        img.onerror = function (e) {
            callBack({
                statusText: "Failed to load image.",
                status: '_FAILED',
            });
            throw new Error("Failed to load image.");
            return;
        };
        img.onload = function(e) {
            if (!('format'in options)) {
                options.format = "image/jpeg";
            }

            if (img.width < options.minWidth) {
                callBack({
                    statusText: "Your photo's width must not be less than " + options.minWidth + "px.",
                    status: '_FAILED',
                });
                throw new Error("Your photo's width must not be less than " + options.minWidth + "px.");
                return;
            }

            if('maintainAll' in options){
                if(options.maintainAll){
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    let dataURL;
                    canvas.height = img.naturalHeight;
                    canvas.width = img.naturalWidth;
                    ctx.drawImage(img, 0, 0);
                    callBack({
                        image: canvas.toDataURL("image/jpeg", imageQuality),
                        dimension: {
                            width: img.width,
                            height: img.height,
                            minWidth: options.minWidth
                        },
                        status: '_OK',
                        statusText: "Image successfully created.",
                    });
                    delete canvas;
                    return;
                }
            }

            CustomPhotoProcessor.getEXIFData(img, function() {
                allMetaData = EXIF.getAllTags(this);
                orientation = 1;
                // orientation = (typeof allMetaData.Orientation === "undefined" || typeof allMetaData.Orientation === null) ? 1 : allMetaData.Orientation;
                aspc = CustomPhotoProcessor.calculateAspectRatioFit(img.width, img.height, options.maxWidthAspectRatio, options.maxHeightAspectRatio);
                img.width = aspc.width;
                img.height = aspc.height;
                
                canvas = document.createElement("CANVAS");
                canvas.width = orientation != 1 ? 3000 : img.width,
                canvas.height = orientation != 1 ? 3000 : img.height;
                ctx = canvas.getContext("2d", { willReadFrequently: true });
                CustomPhotoProcessor.handle_transform(orientation, ctx, canvas.width, canvas.height);
                ctx.drawImage(img, 0, 0, img.width, img.height);
                nimg = CustomPhotoProcessor.trimCanvas(canvas).toDataURL(options.format, imageQuality);
                
                callBack({
                    image: nimg,
                    dimension: {
                        width: img.width,
                        height: img.height,
                        minWidth: options.minWidth
                    },
                    status: '_OK',
                    statusText: "Image successfully created.",
                });
                delete canvas;
            });
        }
        img.onerror = function() {
            callBack({
                status: '_FAILED',
                statusText: "Image couldn't load.",
            });
            throw new Error("Image couldn't load.");
        }
        img.src = data;
    },
    trimCanvas: function(c) {
        try{
            var ctx = c.getContext('2d'), copy = document.createElement('canvas').getContext('2d'), pixels = ctx.getImageData(0, 0, c.width, c.height), l = pixels.data.length, i, bound = {
                top: null,
                left: null,
                right: null,
                bottom: null
            }, x, y;

            for (i = 0; i < l; i += 4) {
                if (pixels.data[i + 3] !== 0) {
                    x = (i / 4) % c.width;
                    y = ~~((i / 4) / c.width);

                    if (bound.top === null) {
                        bound.top = y;
                    }

                    if (bound.left === null) {
                        bound.left = x;
                    } else if (x < bound.left) {
                        bound.left = x;
                    }

                    if (bound.right === null) {
                        bound.right = x;
                    } else if (bound.right < x) {
                        bound.right = x;
                    }

                    if (bound.bottom === null) {
                        bound.bottom = y;
                    } else if (bound.bottom < y) {
                        bound.bottom = y;
                    }
                }
            }

            var trimHeight = bound.bottom - bound.top
            , trimWidth = bound.right - bound.left
            , trimmed = ctx.getImageData(bound.left, bound.top, trimWidth, trimHeight);

            copy.canvas.width = trimWidth;
            copy.canvas.height = trimHeight;
            copy.putImageData(trimmed, 0, 0);

            return copy.canvas;
        }
        catch(err){
			return c;
			CustomPhotoProcessor.togglePUMCLoader(true);
        }
        
    },
    getEXIFData: function(img, callBack) {
        EXIF.getData(img, callBack);
    },
    handle_transform: function(orientation, ctx, width, height) {
        switch (orientation) {
        case 1:
            ctx.transform(1, 0, 0, 1, 0, 0);
            break;
        case 2:
            ctx.transform(-1, 0, 0, 1, width, 0);
            break;
        case 3:
            ctx.transform(-1, 0, 0, -1, width, height);
            break;
        case 4:
            ctx.transform(1, 0, 0, -1, 0, height);
            break;
        case 5:
            ctx.transform(0, 1, 1, 0, 0, 0);
            break;
        case 6:
            ctx.transform(0, 1, -1, 0, height, 0);
            break;
        case 7:
            ctx.transform(0, -1, -1, 0, height, width);
            break;
        case 8:
            ctx.transform(0, -1, 1, 0, 0, width);
            break;
        }
    },
    changeRoute: function(route) {
        var relem = document.getElementsByClassName("pumc-cnt-carrier")
          , relem_length = relem.length;
        for (var i = 0; i < relem_length; i++) {
            relem[i].classList.remove("active");
            if (i == (relem_length - 1)) {
                if (document.getElementsByClassName(route) !== "undefined" || document.getElementsByClassName(route) !== null) {
                    document.getElementsByClassName(route)[0].classList.add("active");
                }
            }
        }
    },
    dataURItoBlob: function(dataURI, options) {
        try {
            var byteString = atob(dataURI.split(',')[1]);
            var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0]
            var ab = new ArrayBuffer(byteString.length);
            var ia = new Uint8Array(ab);
            for (var i = 0; i < byteString.length; i++) {
                ia[i] = byteString.charCodeAt(i);
            }
            
            var blob;
            if(typeof options === "object"){
                blob = new Blob([ab], {
                    type: mimeString
                }, options.name);
            }
            else{
                blob = new Blob([ab], {
                    type: mimeString
                });
            }
            
            return blob;
        } 
        catch (err) {
            //err
        }
    },
    attachMovesToCropImage: function() {
        if(document.getElementById("pumc_crop_image") === "undefined" || document.getElementById("pumc_crop_image") === null) return;
        var sx = CustomPhotoProcessor.sx
          , sy = CustomPhotoProcessor.sy
          , cx = 0
          , cy = 0
          , izmg = document.getElementById("pumc_crop_image")
          , isup = true;

        function ts(e) {
            try {
                if (CustomPhotoProcessor.__PLATFORM__ == "web") {
                    e.preventDefault();
                }
                e = (CustomPhotoProcessor.__PLATFORM__ == "web") ? e : e.touches[0];
                sx = e.clientX - cx;
                sy = e.clientY - cy;
                isup = false;
            } catch (err) {
            }
        }

        function tm(e) {
            try {
                e.preventDefault();
                e = (CustomPhotoProcessor.__PLATFORM__ == "web") ? e : e.touches[0];
                var esx = (e.clientX - sx);
                var esy = (e.clientY - sy);
                if(($("#pumc_crop_image").position().top > $(".pumc-focal-point").position().top) || ($("#pumc_crop_image").position().left > $(".pumc-focal-point").position().left)){
                    if($("#pumc_crop_image").position().top > $(".pumc-focal-point").position().top){
                        esy = (($(".pumc-focal-point").position().top - $(".pumc-crop-image-wrapper").position().top) - 1);
                    }
                    if($("#pumc_crop_image").position().left > $(".pumc-focal-point").position().left){
                        esx = (($(".pumc-focal-point").position().left - $(".pumc-crop-image-wrapper").position().left) - 1);
                    }
                }
                if((($("#pumc_crop_image").position().top + $("#pumc_crop_image")[0].clientHeight) < ($(".pumc-focal-point").position().top + $(".pumc-focal-point")[0].clientHeight)) || (($("#pumc_crop_image").position().left + $("#pumc_crop_image")[0].clientWidth) < ($(".pumc-focal-point").position().left + $(".pumc-focal-point")[0].clientWidth))){
                    if((($("#pumc_crop_image").position().top + $("#pumc_crop_image")[0].clientHeight) < ($(".pumc-focal-point").position().top + $(".pumc-focal-point")[0].clientHeight))){
                        esy = ((($(".pumc-focal-point").position().top + $(".pumc-focal-point")[0].clientHeight) - ($(".pumc-crop-image-wrapper").position().top + $(".pumc-crop-image-wrapper")[0].clientHeight)) + 7);
                    }
                    if((($("#pumc_crop_image").position().left + $("#pumc_crop_image")[0].clientWidth) < ($(".pumc-focal-point").position().left + $(".pumc-focal-point")[0].clientWidth))){
                        esx = ((($(".pumc-focal-point").position().left + $(".pumc-focal-point")[0].clientWidth) - ($(".pumc-crop-image-wrapper").position().left + $(".pumc-crop-image-wrapper")[0].clientWidth)) + 2);
                    }
                }
                isup || (this.style.webkitTransform = 'translate3d(' + esx + 'px,' + esy + 'px,0)');
            } catch (err) {
				
            }
        }

        function te(e) {
            try {
                e = (CustomPhotoProcessor.__PLATFORM__ == "web") ? e : e.changedTouches[0];
                cx = e.clientX - sx;
                cy = e.clientY - sy;
                isup = true;
            } catch (err) {
                
            }
        }

        izmg.addEventListener('mousedown', ts, false);
        izmg.addEventListener('mousemove', tm, false);
        izmg.addEventListener('mouseup', te, false);
        izmg.addEventListener('touchstart', ts, false);
        izmg.addEventListener('touchmove', tm, false);
        izmg.addEventListener('touchend', te, false);

        if (document.getElementById("done_cropping_btn") !== "undefined" && document.getElementById("done_cropping_btn") !== null) {
            document.getElementById("done_cropping_btn").addEventListener("click", function(){
                cx = cy = 0; 
            }, false);
        }
    },
    getCropUImageDimension: function() {
        
    },
    getTransform : function(el) {
        var transform;
        transform = window.getComputedStyle(el, null).getPropertyValue('-webkit-transform');

        function rotate_degree(matrix) {
          if(matrix !== 'none') {
              var values = matrix.split('(')[1].split(')')[0].split(',');
              var a = values[0];
              var b = values[1];
              var angle = Math.round(Math.atan2(b, a) * (180/Math.PI));
          } else { 
            var angle = 0; 
          }
          return (angle < 0) ? angle +=360 : angle;
            }

        var results = transform.match(/matrix(?:(3d)\(-{0,1}\d+\.?\d*(?:, -{0,1}\d+\.?\d*)*(?:, (-{0,1}\d+\.?\d*))(?:, (-{0,1}\d+\.?\d*))(?:, (-{0,1}\d+\.?\d*)), -{0,1}\d+\.?\d*\)|\(-{0,1}\d+\.?\d*(?:, -{0,1}\d+\.?\d*)*(?:, (-{0,1}\d+\.?\d*))(?:, (-{0,1}\d+\.?\d*))\))/);


        var result = [0,0,0];
        if(results){
            if(results[1] == '3d'){
            result = results.slice(2,5);
          } else {
            results.push(0);
            result = results.slice(5, 9); // returns the [X,Y,Z,1] value;
          }

          result.push(rotate_degree(transform));
        };
        return result;
    },
    __PLATFORM: function() {
        var ua = navigator.userAgent;

        var dev = "web";
        if (ua.match(/Linux/i)) {
            dev = "web";
        }
        if (ua.match(/Macintosh/i)) {
            dev = "web";
        }
        if (ua.match(/mac os x/i)) {
            dev = "web";
        }
        if (ua.match(/Windows/i)) {
            dev = "web";
        }
        if (ua.match(/win32/i)) {
            dev = "web";
        }
        if (ua.match(/Android/i)) {
            dev = "mobile";
        }
        if (ua.match(/Iphone/i)) {
            dev = "mobile";
        }
        if (ua.match(/Ipad/i)) {
            dev = "mobile";
        }
        if (ua.match(/iemobile/i)) {
            dev = "mobile";
        }
        if (ua.match(/WPDesktop/i)) {
            dev = "mobile";
        }
        if (ua.match(/Windows Phone/i)) {
            dev = "mobile";
        }
        return dev;
    },
    togglePUMCLoader: function(state) {
        if (document.getElementById("pumc_container_loader") !== "undefined" && document.getElementById("pumc_container_loader") !== null) {
            switch (state) {
            case true:
                document.getElementById("pumc_container_loader").style.display = "block";
                break;
            default:
                document.getElementById("pumc_container_loader").style.display = "none";
                break;
            }
        }
    },
    cropPhoto: function(callBack, dimension, format) {
        format = format !== null ? format : "image/jpeg";
        CustomPhotoProcessor.crop_image_dimension = {
            width: (typeof dimension === "object" && ('width' in dimension)) ? dimension.width : 0,
            height: (typeof dimension === "object" && ('height' in dimension)) ? dimension.height : 0,
            focusAreaWidth_parent: document.getElementsByClassName("pumc-focal-point-parent")[0].clientWidth,
            focusAreaHeight_parent: document.getElementsByClassName("pumc-focal-point-parent")[0].clientHeight,
            focusAreaWidth: document.getElementsByClassName("pumc-focal-point")[0].clientWidth,
            focusAreaHeight: document.getElementsByClassName("pumc-focal-point")[0].clientHeight,
            fawl: (document.getElementsByClassName("pumc-focal-point-parent")[0].offsetLeft + $(window).scrollLeft()) - (document.getElementsByClassName("pumc-focal-point")[0].offsetLeft + $(window).scrollLeft()),
            fawt: (document.getElementsByClassName("pumc-focal-point-parent")[0].offsetTop + $(window).scrollTop()) - (document.getElementsByClassName("pumc-focal-point")[0].offsetTop + $(window).scrollTop())
        };

        setTimeout(function() {
            var canvas = document.createElement("CANVAS")
              , lw = (Math.abs(CustomPhotoProcessor.crop_image_dimension.fawl) * 2)
              , lh = (Math.abs(CustomPhotoProcessor.crop_image_dimension.fawt) * 2);
            ctx = canvas.getContext("2d", { willReadFrequently: true });
            canvas.width = CustomPhotoProcessor.crop_image_dimension.focusAreaWidth_parent;
            canvas.height = CustomPhotoProcessor.crop_image_dimension.focusAreaHeight_parent;
            ctx.drawImage($("#pumc_crop_image")[0], ($("#pumc_crop_image").position().left + $(window).scrollLeft()) + CustomPhotoProcessor.crop_image_dimension.fawl, ($("#pumc_crop_image").position().top + $(window).scrollTop()) + CustomPhotoProcessor.crop_image_dimension.fawt);
            ctx.clearRect(canvas.width - lw, 0, lw, CustomPhotoProcessor.crop_image_dimension.focusAreaHeight);
            ctx.clearRect(0, canvas.height - lh, canvas.width, lh);
            callBack(CustomPhotoProcessor.trimCanvas(canvas).toDataURL(format, imageQuality));
			delete canvas;
        }, 1000);
    },
    linkToImage : function(link, cb){
        try{
            var img = new Image(), canvas = document.createElement("CANVAS"), ctx;
            img.onerror = function (e) {
                cb({
                    photo : null,
                    status : "failed"
                });
            };
            img.onload = function(){
                canvas.width = img.width,
                canvas.height = img.height;
                ctx = canvas.getContext("2d", { willReadFrequently: true });
                ctx.drawImage(img, 0, 0);
                cb({
                    photo : canvas.toDataURL("image/jpeg", imageQuality),
                    status : "ok"
                });
            };
            img.onerror = function(){
                cb({
                    photo : null,
                    status : "failed"
                });
            };
            img.src = link;
        }
        catch(err){
            cb({
                photo : null,
                status : "failed"
            });
        }
        
    }
}

CustomPhotoProcessor.__init__();
