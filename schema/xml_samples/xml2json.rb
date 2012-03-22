# install crack:
#   $sudo gem install crack
#
# run script
#   $ruby xml2json.rb

require 'json'
require 'crack'

print 'Insert file name: '
filename = gets.chomp

my_xml = Crack::XML.parse File.read filename

my_json = my_xml.to_json

f = File.new(filename.split('.')[0] + '.json', 'w')
f.write my_json
f.close

print 'Created file ' + filename.split('.')[0] + '.json'
